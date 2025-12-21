<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatbotService
{
    public function reply(string $message, ?int $userId = null): array
    {
        // Kiểm tra các câu hỏi liên quan đến đơn hàng trước (bảo mật: cần userId để trả lời chi tiết)
        if ($orderAnswer = $this->answerOrderQuestion($message, $userId)) {
            return $orderAnswer;
        }

        // Nếu là câu hỏi chi tiết về 1 sản phẩm cụ thể (màu sắc, dung lượng, ...)
        if ($detailAnswer = $this->answerProductDetailQuestion($message, $userId)) {
            return $detailAnswer;
        }

        // Nếu là câu hỏi về số lượng tồn kho
        if ($inventoryAnswer = $this->answerInventoryQuestion($message)) {
            return $inventoryAnswer;
        }

        // Nếu là câu hỏi về sản phẩm bán chạy
        if ($bestSellingAnswer = $this->answerBestSellingQuestion($message)) {
            return $bestSellingAnswer;
        }

        // Nếu là câu hỏi về sản phẩm bán chạy
        if ($bestSellingAnswer = $this->answerBestSellingQuestion($message)) {
            return $bestSellingAnswer;
        }

        $filters = $this->extractFilters($message);
        $coupons = $this->fetchCoupons($userId);
        
        // Nếu user hỏi về mã khuyến mãi của mình, chỉ lấy coupons, không cần products
        if ($filters['ask_my_coupons']) {
            $context = $this->buildContextSummaryForCoupons($coupons, $userId);
            
            return [
                'answer' => $this->generateAnswer($message, $context, true),
                'suggestions' => collect(),
                'coupons' => $coupons->map(function (Coupon $coupon) {
                    return [
                        'code' => $coupon->code,
                        'type' => $coupon->discount_type,
                        'value' => $coupon->discount_value,
                        'expires_at' => optional($coupon->expires_at)->toDateString(),
                    ];
                }),
                'filters' => $filters,
            ];
        }

        $products = $this->fetchProducts($filters);
        $context = $this->buildContextSummary($products, $coupons);

        return [
            'answer' => $this->generateAnswer($message, $context),
            'suggestions' => $products->map(function (Product $product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $this->resolveDisplayPrice($product),
                    'slug' => $product->slug,
                ];
            }),
            'coupons' => $coupons->map(function (Coupon $coupon) {
                return [
                    'code' => $coupon->code,
                    'type' => $coupon->discount_type,
                    'value' => $coupon->discount_value,
                    'expires_at' => optional($coupon->expires_at)->toDateString(),
                ];
            }),
            'filters' => $filters,
        ];
    }

    /**
     * Trả lời câu hỏi liên quan đến đơn hàng: tra cứu trạng thái, liệt kê đơn gần đây của user.
     */
    protected function answerOrderQuestion(string $message, ?int $userId = null): ?array
    {
        $normalized = Str::lower($message);
        $normalizedAscii = Str::lower(Str::ascii($message));

        // Các từ khóa ngữ cảnh liên quan đến đơn hàng
        $orderKeywords = ['đơn hàng', 'don hang', 'mã đơn', 'ma don', 'mã đơn hàng', 'theo dõi đơn', 'trạng thái đơn', 'tình trạng đơn', 'order'];
        if (! Str::contains($normalized, $orderKeywords) && ! Str::contains($normalizedAscii, $orderKeywords)) {
            return null;
        }

        // Thử tìm order id trong câu: các dạng "#123", "mã đơn 123", "đơn 123"
        $orderId = null;
        if (preg_match('/#(\d{2,9})/u', $message, $m)) {
            $orderId = (int) $m[1];
        } elseif (preg_match('/(?:mã đơn|ma don|don|đơn)\s*[:#]?\s*(\d{2,9})/iu', $message, $m)) {
            $orderId = (int) $m[1];
        } elseif (preg_match('/order\s*(\d{2,9})/iu', $message, $m)) {
            $orderId = (int) $m[1];
        }

        // Nếu user hỏi chung chung về "đơn hàng của tôi" -> liệt kê đơn gần đây
        $askMyOrders = Str::contains($normalized, ['của tôi', 'của minh', 'của mình', 'của tôi?', 'của tôi']) || Str::contains($normalizedAscii, ['cua toi', 'cua minh']);

        if ($orderId) {
            // Nếu có orderId, yêu cầu user đăng nhập để bảo mật nếu chưa có userId
            if (! $userId) {
                return [
                    'answer' => 'Mình cần bạn đăng nhập để tra cứu thông tin đơn hàng. Vui lòng đăng nhập hoặc cung cấp thông tin xác thực (số điện thoại hoặc email) để mình hỗ trợ nhé.',
                    'suggestions' => collect(),
                    'coupons' => collect(),
                    'filters' => ['order_id' => $orderId],
                ];
            }

            $order = Order::where('id', $orderId)->where('user_id', $userId)->first();
            if (! $order) {
                return [
                    'answer' => "Không tìm thấy đơn hàng #{$orderId} trong tài khoản của bạn. Vui lòng kiểm tra lại mã đơn hoặc liên hệ hỗ trợ.",
                    'suggestions' => collect(),
                    'coupons' => collect(),
                    'filters' => ['order_id' => $orderId],
                ];
            }

            $lines = [];
            $lines[] = "📦 Đơn hàng #{$order->id}";
            $lines[] = "💰 Tổng tiền: " . number_format($order->total ?? 0, 0, ',', '.') . 'đ';
            $lines[] = "📝 Trạng thái đơn: {$order->getStatusLabelAttribute()}";
            $lines[] = "💳 Thanh toán: {$order->getPaymentStatusLabelAttribute()}";
            $lines[] = "🚚 Vận chuyển: {$order->getShippingStatusLabelAttribute()}";

            if ($order->shipping_phone) {
                $lines[] = "👤 Người nhận: {$order->shipping_full_name} ({$order->shipping_phone})";
            }

            $answer = implode("\n", $lines);

            $suggestion = [
                'order_id' => $order->id,
                'status' => $order->status,
                'total' => $order->total,
            ];

            return [
                'answer' => $answer,
                'suggestions' => collect([$suggestion]),
                'coupons' => collect(),
                'filters' => ['order_id' => $order->id],
            ];
        }

        if ($askMyOrders) {
            if (! $userId) {
                return [
                    'answer' => 'Mình cần bạn đăng nhập để hiển thị danh sách đơn hàng của bạn. Vui lòng đăng nhập để tiếp tục.',
                    'suggestions' => collect(),
                    'coupons' => collect(),
                    'filters' => [],
                ];
            }

            $orders = Order::where('user_id', $userId)->orderByDesc('created_at')->limit(5)->get();
            if ($orders->isEmpty()) {
                return [
                    'answer' => 'Bạn hiện chưa có đơn hàng nào. Hãy đặt hàng để mình có thể hỗ trợ theo dõi nhé!',
                    'suggestions' => collect(),
                    'coupons' => collect(),
                    'filters' => [],
                ];
            }

            $lines = $orders->map(function (Order $o) {
                return "#{$o->id}: {$o->getStatusLabelAttribute()} - " . number_format($o->total ?? 0, 0, ',', '.') . 'đ';
            })->values()->all();

            $answer = "Danh sách các đơn hàng gần đây của bạn:\n" . implode("\n", $lines);

            $suggestions = $orders->map(function (Order $o) {
                return [
                    'order_id' => $o->id,
                    'status' => $o->status,
                    'total' => $o->total,
                ];
            });

            return [
                'answer' => $answer,
                'suggestions' => $suggestions,
                'coupons' => collect(),
                'filters' => [],
            ];
        }

        return null;
    }

    /**
     * Trả lời các câu hỏi chi tiết cho 1 sản phẩm cụ thể
     * Ví dụ: "iPhone 15 có những màu nào?", "Sản phẩm A có bản 256GB không?"
     */
    protected function answerProductDetailQuestion(string $message, ?int $userId = null): ?array
    {
        $normalized = Str::lower($message);
        $normalizedAscii = Str::lower(Str::ascii($message));

        // Nhận diện câu hỏi liên quan đến màu sắc, bao gồm cả cách viết không dấu / tiếng Anh
        $askColor = Str::contains($normalized, ['màu', 'màu sắc'])
            || Str::contains($normalizedAscii, [
                'mau', 'mau sac',
                'den', 'trang', 'xanh', 'do', 'vang', 'hong', 'tim', 'bac', 'xam',
                'black', 'white', 'blue', 'green', 'red', 'yellow', 'pink', 'purple', 'gold', 'silver', 'gray',
            ]);
        $askStorage = Str::contains($normalized, ['dung lượng', 'bộ nhớ', 'gb']);
        $askVersion = Str::contains($normalized, ['phiên bản', 'version']);
        $askVariant = Str::contains($normalized, ['biến thể', 'loại']);
        // Hỏi về tình trạng còn hàng / hết hàng
        $askAvailability = Str::contains($normalizedAscii, [
            'con hang',
            'het hang',
            'con ko',
            'con khong',
            'con k',
            'con hang khong',
        ]);

        if (! $askColor && ! $askStorage && ! $askVersion && ! $askVariant && ! $askAvailability) {
            return null;
        }

        // 1) Thử tìm sản phẩm khớp với cả câu hỏi
        $productQuery = Product::query()->with(['variants.color', 'variants.storage']);
        $product = $productQuery
            ->where('name', 'like', '%' . $message . '%')
            ->first();

        // 2) Nếu không tìm được theo full câu, thử rút gọn: bỏ các từ dư thừa tiếng Việt
        if (! $product) {
            // Bỏ các từ dư thừa và dung lượng (12GB, 256GB, etc.)
            $clean = str_ireplace(
                [
                    'màu sắc', 'màu', 'mau', 'mau sac',
                    'dung lượng', 'bộ nhớ', 'dung luong', 'bo nho',
                    'bao nhiêu', 'những', 'nào', 'các', 'cac', 'ba nhieu', 'nhung', 'nao',
                    'không', 'ko', 'k', 'khong',
                    'hãy', 'giới thiệu', 'cho tôi', 'cho minh', 'hay', 'gioi thieu', 'cho toi', 'cho minh',
                    'có', 'co',
                    'gì', 'gi',
                    'hiện tại', 'hien tai',
                    '?',
                ],
                '',
                $normalized
            );

            // Bỏ dung lượng (12GB, 256GB, etc.) - cả dạng có và không có dấu cách
            // Nhưng giữ lại RAM (12GB, 8GB) nếu có trong câu hỏi
            $clean = preg_replace('/\b(\d{3,4}\s*gb)\b/i', '', $clean); // Chỉ bỏ storage (256GB, 512GB), giữ RAM (12GB)
            $clean = trim(preg_replace('/\s+/', ' ', $clean));

            if ($clean !== '') {
                // Ưu tiên tìm chính xác trước
                $product = Product::query()
                    ->with(['variants.color', 'variants.storage'])
                    ->where('name', 'like', '%' . $clean . '%')
                    ->first();

                // Nếu vẫn chưa tìm được, thử tìm với từ khóa chính (bỏ số và ký tự đặc biệt)
                if (! $product) {
                    // Tách các từ khóa chính: "Samsung Galaxy Z Fold7" -> ["samsung", "galaxy", "z", "fold7"]
                    $keywords = preg_split('/\s+/', $clean);
                    $mainKeywords = array_filter($keywords, function($word) {
                        $word = trim($word);
                        // Giữ lại các từ có ít nhất 2 ký tự và không chỉ là số
                        return strlen($word) >= 2 && !preg_match('/^\d+$/', $word);
                    });
                    
                    if (!empty($mainKeywords)) {
                        // Tìm sản phẩm có chứa tất cả từ khóa chính (AND logic) để chính xác hơn
                        $products = Product::query()
                            ->with(['variants.color', 'variants.storage'])
                            ->where(function($q) use ($mainKeywords) {
                                foreach ($mainKeywords as $keyword) {
                                    $q->where('name', 'like', '%' . $keyword . '%');
                                }
                            })
                            ->get();
                        
                        if ($products->isNotEmpty()) {
                            // Nếu có nhiều kết quả, ưu tiên sản phẩm có nhiều từ khóa khớp nhất
                            $products = $products->sortByDesc(function($p) use ($mainKeywords) {
                                $name = Str::lower($p->name);
                                $matchCount = 0;
                                foreach ($mainKeywords as $kw) {
                                    if (Str::contains($name, $kw)) {
                                        $matchCount++;
                                    }
                                }
                                return $matchCount;
                            })->values();
                            
                            $product = $products->first();
                        }
                    }
                }
            }
        }

        // 3) Vẫn chưa có: tách từ khoá quan trọng (dùng phiên bản không dấu) và bắt buộc tất cả phải xuất hiện trong tên sản phẩm
        if (! $product) {
            // Dùng chuỗi không dấu để xử lý tốt hơn cả khi người dùng gõ không dấu
            $normalizedAsciiTokens = Str::lower(Str::ascii($message));

            $stopWords = [
                'mau', 'mau sac', 'dung luong', 'bo nho',
                'bao', 'nhieu', 'nhung', 'nao', 'khong', 'ko', 'k', 'hay',
                'gioi', 'thieu', 'cho', 'toi', 'minh', 'co',
                'xin', 'chao', 'san', 'pham',
                'hien', 'con', 'hang', 'hien tai',
                'phien', 'ban',
                'cac', 'gi', 'gi',
                'co', 'nhung', 'nhung gi',
            ];

            $colorTokens = [
                'mau', 'mau sac',
                'den', 'trang', 'xanh', 'do', 'vang', 'hong', 'tim', 'bac', 'xam',
                'black', 'white', 'blue', 'green', 'red', 'yellow', 'pink', 'purple', 'gold', 'silver', 'gray',
            ];

            $words = array_filter(preg_split('/\s+/', $normalizedAsciiTokens), function ($word) use ($stopWords, $colorTokens) {
                $word = trim($word);
                if ($word === '') {
                    return false;
                }

                if (in_array($word, $stopWords, true)) {
                    return false;
                }

                // Chỉ giữ lại từ khoá chữ cái / số ASCII (iphone, galaxy, 15, 256, ...)
                if (! preg_match('/^[a-z0-9]+$/', $word)) {
                    return false;
                }

                // Bỏ qua các token chỉ thể hiện màu sắc, vì màu nằm ở biến thể chứ không phải tên sản phẩm
                if (in_array($word, $colorTokens, true)) {
                    return false;
                }

                // Bỏ qua các token dung lượng dạng "128gb", "256gb", ... vì dung lượng thường nằm ở biến thể, không có trong tên sản phẩm
                if (preg_match('/\d{2,4}gb/', $word)) {
                    return false;
                }

                return true;
            });

            if (! empty($words)) {
                // Lấy các từ khóa quan trọng nhất (ít nhất 3 ký tự, ưu tiên các từ dài hơn)
                $importantWords = array_filter($words, function($w) {
                    return strlen($w) >= 3;
                });
                
                // Nếu có từ khóa quan trọng, chỉ dùng chúng; nếu không thì dùng tất cả
                $searchWords = !empty($importantWords) ? array_values($importantWords) : array_values($words);
                
                // Giới hạn số từ khóa để tìm kiếm (tối đa 4 từ)
                $searchWords = array_slice($searchWords, 0, 4);
                
                if (!empty($searchWords)) {
                    $products = Product::query()
                        ->with(['variants.color', 'variants.storage'])
                        ->where(function ($q) use ($searchWords) {
                            // Tìm sản phẩm có chứa ít nhất một từ khóa quan trọng (OR)
                            // Nhưng ưu tiên sản phẩm có nhiều từ khóa hơn
                            $q->where(function($subQ) use ($searchWords) {
                                foreach ($searchWords as $w) {
                                    $subQ->orWhere('name', 'like', '%' . $w . '%');
                                }
                            });
                        })
                        ->get();

                    if ($products->isNotEmpty()) {
                        // Sắp xếp theo số từ khóa khớp (sản phẩm có nhiều từ khóa khớp hơn sẽ được ưu tiên)
                        // Ưu tiên các từ khóa đặc trưng (fold, flip, pro, max, s24, z, etc.)
                        $products = $products->sortByDesc(function($p) use ($searchWords, $normalized) {
                            $name = Str::lower($p->name);
                            $matchCount = 0;
                            $specialMatchBonus = 0;
                            
                            foreach ($searchWords as $w) {
                                if (Str::contains($name, $w)) {
                                    $matchCount++;
                                    // Tăng điểm nếu từ khóa đặc trưng khớp
                                    if (in_array($w, ['fold', 'fold7', 'flip', 'z', 'pro', 'max', 's24', 's25', 'note'])) {
                                        $specialMatchBonus += 2;
                                    }
                                }
                            }
                            
                            // Nếu câu hỏi có từ khóa đặc trưng, ưu tiên sản phẩm có từ khóa đó
                            if (Str::contains($normalized, ['fold', 'flip', 'z fold', 'z flip'])) {
                                if (Str::contains($name, ['fold', 'flip'])) {
                                    $specialMatchBonus += 5;
                                }
                            }
                            if (Str::contains($normalized, ['pro', 'max'])) {
                                if (Str::contains($name, ['pro', 'max'])) {
                                    $specialMatchBonus += 5;
                                }
                            }
                            if (Str::contains($normalized, ['s24', 's25', 's26'])) {
                                if (Str::contains($name, ['s24', 's25', 's26'])) {
                                    $specialMatchBonus += 5;
                                }
                            }
                            
                            return $matchCount * 10 + $specialMatchBonus;
                        })->values();
                        
                        // Nếu người dùng KHÔNG nhắc đến "pro" hoặc "max" thì ưu tiên bản thường
                        if (! Str::contains($normalized, ['pro', 'max', 'fold', 'flip'])) {
                            $product = $products->first(function (Product $p) {
                                $name = Str::lower($p->name);
                                return ! Str::contains($name, ['pro', 'max']);
                            }) ?? $products->first();
                        } else {
                            // Lấy sản phẩm đầu tiên sau khi đã sắp xếp (đã ưu tiên đúng)
                            $product = $products->first();
                        }
                    }
                }
            }
        }

        if (! $product) {
            return null;
        }

        // Chuẩn bị một số tập biến thể để dùng cho câu trả lời
        $availableVariants = $product->variants->filter(function ($v) {
            return ($v->status === 'available') && ($v->stock === null || $v->stock > 0);
        });

        // Thử nhận diện màu mà người dùng hỏi cụ thể (đen, hồng, ...)
        $requestedColorName = null;
        $colorKeywordMap = [
            'Đen'  => ['den', 'black'],
            'Trắng' => ['trang', 'white'],
            'Xanh' => ['xanh', 'blue', 'green'],
            'Hồng' => ['hong', 'pink'],
            'Vàng' => ['vang', 'yellow', 'gold'],
            'Bạc'  => ['bac', 'silver'],
            'Xám'  => ['xam', 'gray', 'grey'],
        ];

        foreach ($colorKeywordMap as $colorName => $keywords) {
            if (Str::contains($normalizedAscii, $keywords)) {
                $requestedColorName = $colorName;
                break;
            }
        }

        $colors = $product->variants
            ->map(function ($v) {
                if ($v->color) {
                    return $v->color->name;
                }

                if ($v->description && preg_match('/màu\s+([^\d,]+)/iu', $v->description, $match)) {
                    return trim($match[1]);
                }

                if ($v->description && preg_match('/^(.+?)(\d{2,4}\s*gb)/iu', $v->description, $match)) {
                    return trim($match[1]);
                }

                return null;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        // Nhận diện RAM trong câu hỏi (12GB, 8GB, ...) - thường là số nhỏ hơn 16GB
        $requestedRam = null;
        if (preg_match_all('/\b(\d{1,2}\s*gb)\b/i', $message, $ramMatches)) {
            // Lấy tất cả các giá trị GB tìm được
            foreach ($ramMatches[1] as $ramMatch) {
                $ramValue = (int) preg_replace('/\s*gb/i', '', $ramMatch);
                // RAM thường là 4, 6, 8, 12, 16GB (không phải 128, 256, 512GB)
                if ($ramValue <= 16) {
                    $requestedRam = strtoupper(preg_replace('/\s+/', '', $ramMatch)); // "12GB"
                    break;
                }
            }
        }

        // Nhận diện dung lượng cụ thể mà người dùng đang hỏi (128GB, 256GB, ...)
        $requestedStorage = null;
        if (preg_match('/\b(\d{3,4}\s*gb)\b/i', $message, $matchStorage)) {
            $requestedStorage = strtoupper(preg_replace('/\s+/', '', $matchStorage[1])); // "256GB"
        }

        // Đảm bảo variants được load với relationships
        if (!$product->relationLoaded('variants')) {
            $product->load(['variants.color', 'variants.storage']);
        }

        // Lọc variants theo RAM nếu có yêu cầu RAM
        $variantsForStorage = $product->variants;
        if ($requestedRam) {
            $normalizedRequestedRam = strtoupper(preg_replace('/\s+/', '', $requestedRam));
            $variantsForStorage = $variantsForStorage->filter(function ($v) use ($normalizedRequestedRam, $product) {
                try {
                    // Kiểm tra trong description
                    if ($v->description) {
                        $description = strtoupper(preg_replace('/\s+/', '', $v->description));
                        if (Str::contains($description, $normalizedRequestedRam)) {
                            return true;
                        }
                    }
                    // Kiểm tra trong tên sản phẩm
                    $productName = strtoupper(preg_replace('/\s+/', '', $product->name));
                    if (Str::contains($productName, $normalizedRequestedRam)) {
                        return true;
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Error filtering variant by RAM', ['error' => $e->getMessage()]);
                }
                return false;
            });
        }

        $storages = $variantsForStorage
            ->map(function ($v) {
                try {
                    if ($v->storage && isset($v->storage->storage)) {
                        return $v->storage->storage;
                    }

                    if ($v->description && preg_match('/\b(\d{3,4}\s*gb)\b/i', $v->description, $match)) {
                        return strtoupper(preg_replace('/\s+/', '', $match[1]));
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Error extracting storage from variant', ['error' => $e->getMessage()]);
                }
                return null;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        $versions = $product->variants
            ->map(function ($v) {
                return $v->version?->name;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        // Nếu người dùng hỏi kèm dung lượng (vd "128GB") thì chỉ lấy biến thể đúng dung lượng đó
        // Cũng lọc theo RAM nếu có yêu cầu RAM
        $variantsForSummary = $product->variants;
        
        // Lọc theo RAM trước nếu có yêu cầu RAM
        if ($requestedRam) {
            $normalizedRequestedRam = strtoupper(preg_replace('/\s+/', '', $requestedRam));
            $variantsForSummary = $variantsForSummary->filter(function ($v) use ($normalizedRequestedRam, $product) {
                try {
                    // Kiểm tra trong description
                    if ($v->description) {
                        $description = strtoupper(preg_replace('/\s+/', '', $v->description));
                        if (Str::contains($description, $normalizedRequestedRam)) {
                            return true;
                        }
                    }
                    // Kiểm tra trong tên sản phẩm
                    $productName = strtoupper(preg_replace('/\s+/', '', $product->name));
                    if (Str::contains($productName, $normalizedRequestedRam)) {
                        return true;
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Error filtering variant by RAM for summary', ['error' => $e->getMessage()]);
                }
                return false;
            });
        }
        
        if ($requestedStorage) {
            // Chuẩn hóa requestedStorage: bỏ khoảng trắng, uppercase
            $normalizedRequestedStorage = strtoupper(preg_replace('/\s+/', '', $requestedStorage));
            
            $variantsForSummary = $variantsForSummary->filter(function ($v) use ($normalizedRequestedStorage) {
                $storageName = null;
                if ($v->storage) {
                    $storageName = strtoupper(preg_replace('/\s+/', '', $v->storage->storage));
                } elseif ($v->description && preg_match('/\b(\d{2,4}\s*gb)\b/i', $v->description, $match)) {
                    $storageName = strtoupper(preg_replace('/\s+/', '', $match[1]));
                }
                
                // So sánh chính xác hoặc kiểm tra xem description có chứa dung lượng không
                if ($storageName === $normalizedRequestedStorage) {
                    return true;
                }
                
                // Fallback: kiểm tra xem description có chứa dung lượng được yêu cầu không
                if ($v->description) {
                    $description = strtoupper(preg_replace('/\s+/', '', $v->description));
                    return Str::contains($description, $normalizedRequestedStorage);
                }

                return false;
            });
        }

        $variantSummaries = $variantsForSummary->map(function ($v) {
            $parts = [];

            if ($v->version?->name) {
                $parts[] = $v->version->name;
            }

            if ($v->storage?->storage) {
                $parts[] = $v->storage->storage;
            }

            if ($v->color?->name) {
                $parts[] = 'màu ' . $v->color->name;
            } elseif ($v->description && preg_match('/màu\s+([^\d,]+)/iu', $v->description, $match)) {
                $parts[] = 'màu ' . trim($match[1]);
            }

            $label = $parts ? implode(' - ', $parts) : ($v->description ?: $v->sku);

            $price = number_format($v->price, 0, ',', '.');

            if ($v->price_sale !== null && $v->price_sale < $v->price) {
                $salePrice = number_format($v->price_sale, 0, ',', '.');
                return "• {$label} (giá niêm yết {$price}đ, giá khuyến mãi {$salePrice}đ)";
            }

            return "• {$label} (giá {$price}đ)";
        })->unique()->values()->all();

        $parts = [];

        // Xây dựng câu trả lời theo mức độ cụ thể của câu hỏi
        if ($askColor && $requestedStorage) {
            // Người dùng hỏi rõ: màu cho một dung lượng cụ thể (vd "128GB")
            $colorsForRequestedStorage = $variantsForSummary
                ->map(function ($v) {
                    // Lấy màu từ color relationship
                    if ($v->color) {
                        return $v->color->name;
                    }
                    // Hoặc từ description
                    if ($v->description && preg_match('/màu\s+([^\d,]+)/iu', $v->description, $match)) {
                        return trim($match[1]);
                    }
                    return null;
                })
                ->filter()
                ->unique()
                ->values()
                ->all();

            if ($colorsForRequestedStorage) {
                $parts[] = 'Với phiên bản ' . $requestedStorage . ' của ' . $product->name . ' hiện có các màu: ' . implode(', ', $colorsForRequestedStorage) . '.';
            } else {
                // Nếu không tìm thấy màu cho dung lượng cụ thể, fallback về tất cả màu của sản phẩm
                $allColors = $product->variants
                    ->map(function ($v) {
                        if ($v->color) {
                            return $v->color->name;
                        }
                        if ($v->description && preg_match('/màu\s+([^\d,]+)/iu', $v->description, $match)) {
                            return trim($match[1]);
                        }
                        return null;
                    })
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();
                
                if ($allColors) {
                    $parts[] = 'Hiện mình không tìm thấy thông tin màu sắc cụ thể cho bản ' . $requestedStorage . ' của ' . $product->name . '.';
                    $parts[] = 'Tuy nhiên, ' . $product->name . ' hiện có các màu: ' . implode(', ', $allColors) . '.';
                } else {
                    $parts[] = 'Hiện mình không tìm thấy thông tin màu sắc cho bản ' . $requestedStorage . ' của ' . $product->name . '.';
                }
            }

            if ($variantSummaries) {
                $parts[] = "Chi tiết các biến thể " . $requestedStorage . ":\n" . implode("\n", $variantSummaries);
            }
        } else {
            // Trường hợp chung: giữ nguyên hành vi cũ
            if ($askColor) {
                if ($colors) {
                    $parts[] = 'Sản phẩm ' . $product->name . ' hiện có các màu: ' . implode(', ', $colors) . '.';
                } else {
                    $parts[] = 'Hiện mình không tìm thấy thông tin màu sắc chi tiết cho ' . $product->name . '.';
                }
            }

            if ($askStorage) {
                if ($storages) {
                    $parts[] = 'Các phiên bản dung lượng/bộ nhớ đang có: ' . implode(', ', $storages) . '.';
                } else {
                    $parts[] = 'Chưa có thông tin dung lượng/bộ nhớ chi tiết cho ' . $product->name . '.';
                }
            }

            if ($askVersion || $askVariant) {
                if ($versions) {
                    $parts[] = 'Các phiên bản hiện có: ' . implode(', ', $versions) . '.';
                }

                if ($variantSummaries) {
                    $parts[] = "Chi tiết các biến thể:\n" . implode("\n", $variantSummaries);
                }
            }
        }

        if ($askAvailability) {
            if ($requestedColorName) {
                // Nếu người dùng hỏi rõ "màu Đen còn hàng không" thì ưu tiên trả lời đúng trọng tâm,
                // không cần liệt kê lại toàn bộ màu/biến thể bên trên.
                $parts = [];

                $matchingColorAvailable = $availableVariants->filter(function ($v) use ($requestedColorName) {
                    return $v->color && Str::lower($v->color->name) === Str::lower($requestedColorName);
                });

                if ($matchingColorAvailable->isNotEmpty()) {
                    $parts[] = "Phiên bản màu {$requestedColorName} của {$product->name} hiện đang còn hàng.";
                } else {
                    $parts[] = "Hiện phiên bản màu {$requestedColorName} của {$product->name} không còn hàng hoặc không tồn tại.";
                }
            } else {
                if ($availableVariants->isNotEmpty()) {
                    $parts[] = $product->name . ' hiện vẫn còn hàng ở một số phiên bản.';
                } else {
                    $parts[] = 'Hiện ' . $product->name . ' đã hết hàng.';
                }
            }
        }

        $answer = implode(' ', $parts);

        return [
            'answer' => $answer,
            'suggestions' => collect([$product])->map(function (Product $p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->price,
                    'slug' => $p->slug,
                ];
            }),
            'coupons' => collect(), // Giữ trống cho câu hỏi chi tiết
            'filters' => [
                'detail_for' => $product->id,
            ],
        ];
    }

    /**
     * Trả lời câu hỏi về số lượng tồn kho của sản phẩm
     * Ví dụ: "số lượng tồn kho của iPhone 15", "iPhone 15 còn bao nhiêu", "tồn kho iPhone 15"
     */
    protected function answerInventoryQuestion(string $message): ?array
    {
        $normalized = Str::lower($message);
        $normalizedAscii = Str::lower(Str::ascii($message));

        // Nhận diện câu hỏi về số lượng tồn kho
        $inventoryKeywords = [
            'số lượng tồn kho', 'so luong ton kho', 'ton kho',
            'còn bao nhiêu', 'con bao nhieu', 'còn mấy', 'con may',
            'số lượng còn', 'so luong con', 'còn hàng bao nhiêu', 'con hang bao nhieu',
            'tồn kho hiện tại', 'ton kho hien tai', 'số lượng hiện tại', 'so luong hien tai',
            'inventory', 'stock', 'quantity',
        ];

        $hasInventoryKeyword = false;
        foreach ($inventoryKeywords as $keyword) {
            if (Str::contains($normalized, $keyword) || Str::contains($normalizedAscii, $keyword)) {
                $hasInventoryKeyword = true;
                break;
            }
        }

        if (!$hasInventoryKeyword) {
            return null;
        }

        // Tìm sản phẩm được đề cập trong câu hỏi
        $product = $this->findProductInMessage($message, $normalized, $normalizedAscii);

        if (!$product) {
            return null;
        }

        // Load variants với stock và các relationships
        $product->load(['variants.version', 'variants.storage', 'variants.color']);

        // Tính tổng số lượng tồn kho từ tất cả các variant
        $totalStock = $product->variants->sum(function ($variant) {
            return $variant->stock ?? 0;
        });

        // Đếm số variant còn hàng
        $availableVariants = $product->variants->filter(function ($variant) {
            return ($variant->status === 'available' || $variant->status === null) 
                && ($variant->stock ?? 0) > 0;
        })->count();

        // Xây dựng câu trả lời
        $answer = "Số lượng tồn kho hiện tại của {$product->name} là: " . number_format($totalStock, 0, ',', '.') . " sản phẩm.";
        
        if ($availableVariants > 0) {
            $answer .= " Hiện có {$availableVariants} biến thể đang còn hàng.";
        } else {
            $answer .= " Hiện sản phẩm đã hết hàng.";
        }

        // Nếu có nhiều variant, có thể thêm thông tin chi tiết
        if ($product->variants->count() > 1 && $totalStock > 0) {
            $variantDetails = $product->variants
                ->filter(function ($variant) {
                    return ($variant->stock ?? 0) > 0;
                })
                ->map(function ($variant) {
                    $parts = [];
                    if ($variant->version?->name) {
                        $parts[] = $variant->version->name;
                    }
                    if ($variant->storage?->storage) {
                        $parts[] = $variant->storage->storage;
                    }
                    if ($variant->color?->name) {
                        $parts[] = 'màu ' . $variant->color->name;
                    }
                    $label = $parts ? implode(' - ', $parts) : ($variant->description ?: $variant->sku);
                    return "• {$label}: " . number_format($variant->stock ?? 0, 0, ',', '.') . " sản phẩm";
                })
                ->take(5)
                ->implode("\n");
            
            if ($variantDetails) {
                $answer .= "\n\nChi tiết theo biến thể:\n{$variantDetails}";
            }
        }

        return [
            'answer' => $answer,
            'suggestions' => collect([$product])->map(function (Product $p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $this->resolveDisplayPrice($p),
                    'slug' => $p->slug,
                ];
            }),
            'coupons' => collect(),
            'filters' => [
                'inventory_for' => $product->id,
            ],
        ];
    }

    /**
     * Tìm sản phẩm được đề cập trong câu hỏi
     */
    protected function findProductInMessage(string $message, string $normalized, string $normalizedAscii): ?Product
    {
        // 1) Thử tìm sản phẩm khớp với cả câu hỏi
        $product = Product::query()
            ->where('name', 'like', '%' . $message . '%')
            ->first();

        // 2) Nếu không tìm được, làm sạch câu hỏi và tìm lại
        if (!$product) {
            $clean = str_ireplace(
                [
                    'số lượng tồn kho', 'so luong ton kho', 'ton kho',
                    'còn bao nhiêu', 'con bao nhieu', 'còn mấy', 'con may',
                    'số lượng còn', 'so luong con', 'còn hàng bao nhiêu', 'con hang bao nhieu',
                    'tồn kho hiện tại', 'ton kho hien tai', 'số lượng hiện tại', 'so luong hien tai',
                    'của', 'cua', 'hiện tại', 'hien tai',
                    '?', 'bao nhiêu', 'bao nhieu',
                ],
                '',
                $normalized
            );
            $clean = trim(preg_replace('/\s+/', ' ', $clean));

            if ($clean !== '') {
                $product = Product::query()
                    ->where('name', 'like', '%' . $clean . '%')
                    ->first();
            }
        }

        // 3) Nếu vẫn chưa tìm được, tách từ khóa và tìm
        if (!$product) {
            $stopWords = [
                'so', 'luong', 'ton', 'kho', 'con', 'bao', 'nhieu', 'may',
                'hien', 'tai', 'cua', 'của', 'cua', 'của',
                'san', 'pham', 'sản', 'phẩm',
            ];

            $words = array_filter(preg_split('/\s+/', $normalizedAscii), function ($word) use ($stopWords) {
                $word = trim($word);
                return $word !== '' 
                    && !in_array($word, $stopWords, true)
                    && preg_match('/^[a-z0-9]+$/', $word)
                    && strlen($word) >= 2;
            });

            if (!empty($words)) {
                $importantWords = array_filter($words, function($w) {
                    return strlen($w) >= 3;
                });
                $searchWords = !empty($importantWords) ? array_values($importantWords) : array_values($words);
                $searchWords = array_slice($searchWords, 0, 4);

                if (!empty($searchWords)) {
                    $products = Product::query()
                        ->where(function ($q) use ($searchWords) {
                            $q->where(function($subQ) use ($searchWords) {
                                foreach ($searchWords as $w) {
                                    $subQ->orWhere('name', 'like', '%' . $w . '%');
                                }
                            });
                        })
                        ->get();

                    if ($products->isNotEmpty()) {
                        $products = $products->sortByDesc(function($p) use ($searchWords) {
                            $name = Str::lower($p->name);
                            $matchCount = 0;
                            foreach ($searchWords as $w) {
                                if (Str::contains($name, $w)) {
                                    $matchCount++;
                                }
                            }
                            return $matchCount;
                        })->values();

                        $product = $products->first();
                    }
                }
            }
        }

        return $product;
    }

    protected function extractFilters(string $message): array
    {
        $normalized = Str::lower($message);
        $normalizedAscii = Str::lower(Str::ascii($message));

        $filters = [
            'keyword' => null,
            'category_keyword' => null,
            'min_price' => null,
            'max_price' => null,
            'ask_my_coupons' => false,
        ];

        // Nhận diện câu hỏi về mã khuyến mãi của user
        $hasCouponKeyword = Str::contains($normalized, ['mã khuyến mãi', 'ma khuyen mai', 'mã giảm giá', 'ma giam gia', 'coupon', 'voucher']);
        
        if (!$hasCouponKeyword) {
            $filters['ask_my_coupons'] = false;
        } else {
            // Các từ khóa chỉ ra user đang hỏi về mã khuyến mãi của chính họ
            $myKeywords = [
                'của tôi', 'của mình', 'của bạn', 'của em',
                'mình có', 'tôi có', 'bạn có', 'em có',
                'mình đang', 'tôi đang', 'bạn đang', 'em đang',
                'mình còn', 'tôi còn', 'bạn còn', 'em còn',
                'mã khuyến mãi nào', 'ma khuyen mai nao',
                'những mã khuyến mãi', 'nhung ma khuyen mai',
                'danh sách mã', 'danh sach ma',
            ];
            
            $hasMyKeyword = Str::contains($normalized, $myKeywords) 
                || Str::contains($normalizedAscii, [
                    'cua toi', 'cua minh', 'cua ban', 'cua em',
                    'minh co', 'toi co', 'ban co', 'em co',
                    'minh dang', 'toi dang', 'ban dang', 'em dang',
                    'minh con', 'toi con', 'ban con', 'em con',
                    'ma khuyen mai nao',
                    'nhung ma khuyen mai',
                    'danh sach ma',
                ]);
            
            // Kiểm tra các pattern cụ thể: "mình đang còn", "tôi muốn biết mình", "những mã khuyến mãi nào"
            $hasQuestionPattern = (
                Str::contains($normalized, ['mình đang còn', 'tôi đang còn', 'bạn đang còn', 'em đang còn'])
                || Str::contains($normalizedAscii, ['minh dang con', 'toi dang con', 'ban dang con', 'em dang con'])
                || (Str::contains($normalized, ['muốn biết', 'muon biet']) && Str::contains($normalized, ['mình', 'tôi', 'bạn', 'em']))
                || (Str::contains($normalizedAscii, ['muon biet']) && Str::contains($normalizedAscii, ['minh', 'toi', 'ban', 'em']))
                || Str::contains($normalized, ['muốn biết mình', 'muốn biết tôi', 'muốn biết bạn', 'muốn biết em'])
                || Str::contains($normalizedAscii, ['muon biet minh', 'muon biet toi', 'muon biet ban', 'muon biet em'])
                || (Str::contains($normalized, ['đang còn', 'dang con']) && Str::contains($normalized, ['mã khuyến mãi', 'ma khuyen mai']))
            );
            
            // Nếu có từ khóa về mã khuyến mãi VÀ (có từ khóa về "của tôi/mình" HOẶC pattern câu hỏi)
            $askMyCoupons = $hasMyKeyword || $hasQuestionPattern;
            
            $filters['ask_my_coupons'] = $askMyCoupons;
        }

        // Hỗ trợ nhận diện các biểu thức giá có đơn vị: "triệu", "k", "đ" hay các chữ số thẳng
        // Ví dụ: "10 triệu", "5.5 triệu", "10k", "30.000.000đ", "10000000"
        if (preg_match('/(\d+(?:[.,]\d+)?)\s*(triệu|trieu|tr|k|ngh[ií]n|nghin|ng|đ|vnd|dong)\b/iu', $message, $m)) {
            $num = str_replace(',', '.', $m[1]);
            $unit = Str::lower($m[2]);
            $value = (float) $num;

            if (Str::contains($unit, 'tri')) {
                $price = (int) round($value * 1000000);
            } elseif (in_array($unit, ['k', 'nghìn', 'nghin', 'ng'], true)) {
                $price = (int) round($value * 1000);
            } else {
                // Đơn vị là đ/vnd/dong -> coi là số VND nguyên
                $price = (int) round($value);
            }

            if (Str::contains($normalized, ['cao', 'trên', 'hơn', 'từ'])) {
                $filters['min_price'] = $price;
            } elseif (Str::contains($normalized, ['dưới', 'duoi', 'tối đa', 'toi da', 'không quá', 'khong qua'])) {
                $filters['max_price'] = $price;
            } else {
                $filters['max_price'] = $price;
            }

        } elseif (preg_match('/\b(\d{5,9})\b/', preg_replace('/[^\d]/', ' ', $message), $match)) {
            // Fallback: nếu người dùng nhập số VND liền (ví dụ 10000000)
            $price = (int) $match[1];
            if (Str::contains($normalized, ['cao', 'trên', 'hơn', 'từ'])) {
                $filters['min_price'] = $price;
            } elseif (Str::contains($normalized, ['dưới', 'tối đa', 'không quá'])) {
                $filters['max_price'] = $price;
            } else {
                $filters['max_price'] = $price;
            }
        }

        if (preg_match('/(iphone|samsung|xiaomi|oppo|vivo|realme|macbook|laptop|tai nghe|loa|tablet|điện thoại)/iu', $message, $keyword)) {
            $kw = Str::lower($keyword[1]);
            $filters['keyword'] = $kw;

            // Map một số từ khóa sang category cụ thể
            if (in_array($kw, ['tai nghe', 'loa'])) {
                $filters['category_keyword'] = 'tai nghe';
            } elseif (in_array($kw, ['laptop', 'macbook'])) {
                $filters['category_keyword'] = 'laptop';
            } elseif (in_array($kw, ['điện thoại', 'iphone', 'samsung', 'xiaomi', 'oppo', 'vivo', 'realme'])) {
                $filters['category_keyword'] = 'điện thoại';
            }
        }

        return $filters;
    }

    protected function fetchProducts(array $filters): Collection
    {
        $query = Product::query()->with(['category','variants']);

        if (!empty($filters['category_keyword'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['category_keyword'] . '%');
            });
        }

        // Lọc theo keyword nếu có (có thể kết hợp với category_keyword)
        if (!empty($filters['keyword'])) {
            // Nếu keyword là tên thương hiệu (samsung, iphone, xiaomi, etc.), lọc theo tên sản phẩm
            $brandKeywords = ['samsung', 'iphone', 'xiaomi', 'oppo', 'vivo', 'realme'];
            if (in_array($filters['keyword'], $brandKeywords)) {
                $query->where('name', 'like', '%' . $filters['keyword'] . '%');
            } elseif (empty($filters['category_keyword'])) {
                // Nếu không phải brand keyword và không có category, vẫn lọc theo tên
                $query->where('name', 'like', '%' . $filters['keyword'] . '%');
            }
        }

        if (!empty($filters['min_price'])) {
            $query->whereHas('variants', function ($q) use ($filters) {
                $q->whereRaw('COALESCE(price_sale, price, 0) >= ?', [$filters['min_price']]);
            });
        }

        if (!empty($filters['max_price'])) {
            $query->whereHas('variants', function ($q) use ($filters) {
                $q->whereRaw('COALESCE(price_sale, price, 0) <= ?', [$filters['max_price']]);
            });
        }

        $products = $query->orderByDesc('views')->limit(5)->get();

        // Chỉ fallback về sản phẩm phổ biến khi người dùng không nêu loại sản phẩm cụ thể
        if ($products->isEmpty() && empty($filters['keyword']) && empty($filters['category_keyword']) && empty($filters['min_price']) && empty($filters['max_price'])) {
            $products = Product::with('variants')->orderByDesc('views')->limit(5)->get();
        }

        return $products;
    }

    protected function fetchCoupons(?int $userId = null): Collection
    {
        // Lấy public coupons (chưa hết hạn)
        $publicCoupons = Coupon::where(function ($query) {
                $query->where('type', 'public')
                      ->orWhereNull('type'); // Backward compatibility
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->get();

        // Lấy private coupons của user (nếu có userId)
        $privateCoupons = collect();
        if ($userId) {
            $privateCoupons = Coupon::where('type', 'private')
                ->whereHas('users', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', now());
                })
                ->get();
        }

        // Gộp và giới hạn số lượng
        return $publicCoupons->merge($privateCoupons)
            ->sortBy('expires_at')
            ->take(5)
            ->values();
    }

    protected function buildContextSummary(Collection $products, Collection $coupons): string
    {
        $productLines = $products->map(function (Product $product) {
            $price = $this->resolveDisplayPrice($product);
            return "- {$product->name}: " . number_format($price, 0, ',', '.') . "đ (slug: {$product->slug})";
        })->implode("\n");

        $couponLines = $coupons->map(function (Coupon $coupon) {
            $value = $coupon->discount_type === 'percent'
                ? $coupon->discount_value . '%'
                : number_format($coupon->discount_value, 0, ',', '.') . 'đ';

            $expires = $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Không giới hạn';
            
            $typeLabel = ($coupon->type ?? 'public') === 'private' ? ' (riêng tư)' : '';

            return "- {$coupon->code}: giảm {$value}, hết hạn {$expires}{$typeLabel}";
        })->implode("\n");

        return "Sản phẩm đề xuất:\n{$productLines}\n\nMã khuyến mãi khả dụng:\n{$couponLines}";
    }

    protected function buildContextSummaryForCoupons(Collection $coupons, ?int $userId): string
    {
        if ($coupons->isEmpty()) {
            return "Bạn hiện chưa có mã khuyến mãi nào. Hãy theo dõi để nhận được các mã khuyến mãi hấp dẫn từ chúng tôi!";
        }

        $publicCoupons = $coupons->filter(function ($coupon) {
            return ($coupon->type ?? 'public') === 'public';
        });

        $privateCoupons = $coupons->filter(function ($coupon) {
            return ($coupon->type ?? 'public') === 'private';
        });

        $couponLines = $coupons->map(function (Coupon $coupon) {
            $value = $coupon->discount_type === 'percent'
                ? $coupon->discount_value . '%'
                : number_format($coupon->discount_value, 0, ',', '.') . 'đ';

            $expires = $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Không giới hạn';
            
            $typeLabel = ($coupon->type ?? 'public') === 'private' ? ' (riêng tư - chỉ dành cho bạn)' : ' (công khai)';

            return "- {$coupon->code}: giảm {$value}, hết hạn {$expires}{$typeLabel}";
        })->implode("\n");

        $summary = "Bạn hiện có {$coupons->count()} mã khuyến mãi có thể sử dụng:\n\n{$couponLines}";
        
        if ($publicCoupons->count() > 0 && $privateCoupons->count() > 0) {
            $summary .= "\n\nTrong đó:\n- {$publicCoupons->count()} mã công khai (mọi người đều có thể sử dụng)\n- {$privateCoupons->count()} mã riêng tư (chỉ dành riêng cho bạn)";
        }

        return $summary;
    }

    /**
     * Lấy giá hiển thị của sản phẩm dựa trên biến thể đầu tiên.
     */
    protected function resolveDisplayPrice(Product $product): float
    {
        $variant = $product->variants->first();
        return (float) ($variant?->price_sale ?? $variant?->price ?? 0);
    }

    protected function generateAnswer(string $message, string $context, bool $isCouponOnly = false): string
    {
        $apiKey = config('services.openai.key');
        $model = config('services.openai.model', 'gpt-4o-mini');

        if ($apiKey) {
            $systemPrompt = 'Bạn là trợ lý bán hàng của một cửa hàng điện tử, hãy tư vấn ngắn gọn, thân thiện bằng tiếng Việt và chỉ sử dụng thông tin trong phần context.';
            
            if ($isCouponOnly) {
                $systemPrompt .= ' Khi người dùng hỏi về mã khuyến mãi của họ, hãy chỉ trả lời về mã khuyến mãi, không đề cập đến sản phẩm.';
            }

            try {
                $response = Http::withToken($apiKey)
                    ->timeout(20)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => $model,
                        'temperature' => 0.3,
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $systemPrompt,
                            ],
                            [
                                'role' => 'user',
                                'content' => "Câu hỏi: {$message}\n\nContext:\n{$context}",
                            ],
                        ],
                    ]);

                if ($response->successful()) {
                    return $response->json('choices.0.message.content', '') ?: $this->fallbackResponse($context);
                }
            } catch (\Throwable $e) {
                // Nếu gọi OpenAI lỗi (mạng, key sai, timeout, ...), ghi log và dùng fallback
                \Log::warning('Chatbot OpenAI request failed', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $this->fallbackResponse($context);
    }

    protected function fallbackResponse(string $context): string
    {
        return "Hiện tại tôi đề xuất một vài lựa chọn nổi bật:\n{$context}\nBạn có thể cho tôi biết thêm nhu cầu cụ thể để mình tư vấn chính xác hơn nhé!";
    }


    /**
     * Trả lời câu hỏi về sản phẩm bán chạy
     */
    protected function answerBestSellingQuestion(string $message): ?array
    {
        $normalized = Str::lower($message);
        $normalizedAscii = Str::lower(Str::ascii($message));

        $keywords = ['bán chạy', 'ban chay', 'hot', 'top', 'mua nhiều', 'mua nhieu', 'best seller'];
        $hasKeyword = false;
        foreach ($keywords as $keyword) {
            if (Str::contains($normalized, $keyword) || Str::contains($normalizedAscii, $keyword)) {
                $hasKeyword = true;
                break;
            }
        }

        if (!$hasKeyword) {
            return null;
        }

        // Default 30 days
        $end = Carbon::now()->endOfDay();
        $start = Carbon::now()->subDays(30)->startOfDay();

        $topProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->where('orders.payment_status', 1)
            ->whereBetween('orders.created_at', [$start, $end])
            ->select(
                'order_items.product_id',
                'order_items.product_name',
                DB::raw('SUM(order_items.total_price) as revenue'),
                DB::raw('SUM(order_items.quantity) as quantity_sold')
            )
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('quantity_sold')
            ->limit(5)
            ->get();

        if ($topProducts->isEmpty()) {
             return [
                'answer' => 'Hiện tại chưa có đủ dữ liệu về sản phẩm bán chạy trong 30 ngày qua.',
                'suggestions' => collect(),
                'coupons' => collect(),
                'filters' => [],
            ];
        }

        $lines = ["Top 5 sản phẩm bán chạy nhất trong 30 ngày qua:"];
        foreach ($topProducts as $index => $item) {
            $rank = $index + 1;
            $lines[] = "{$rank}. {$item->product_name} - Đã bán: {$item->quantity_sold}";
        }
        
        $answer = implode("\n", $lines);

        // Fetch product details for suggestions
        $productIds = $topProducts->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->get();

        $suggestions = $products->map(function ($p) {
             return [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'slug' => $p->slug,
            ];
        });

        return [
            'answer' => $answer,
            'suggestions' => $suggestions,
            'coupons' => collect(),
            'filters' => [],
        ];
    }
}

