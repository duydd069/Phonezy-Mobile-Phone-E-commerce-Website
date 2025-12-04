<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatbotService
{
    public function reply(string $message, ?int $userId = null): array
    {
        // Nếu là câu hỏi chi tiết về 1 sản phẩm cụ thể (màu sắc, dung lượng, ...)
        if ($detailAnswer = $this->answerProductDetailQuestion($message, $userId)) {
            return $detailAnswer;
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
                    'price' => $product->price,
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
            $clean = preg_replace('/\b\d{2,4}\s*gb\b/i', '', $clean);
            $clean = trim(preg_replace('/\s+/', ' ', $clean));

            if ($clean !== '') {
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
                        // Tìm sản phẩm có chứa ít nhất 2 từ khóa chính
                        $product = Product::query()
                            ->with(['variants.color', 'variants.storage'])
                            ->where(function($q) use ($mainKeywords) {
                                $count = 0;
                                foreach ($mainKeywords as $keyword) {
                                    if ($count < 2) { // Chỉ cần 2 từ khóa đầu tiên
                                        $q->where('name', 'like', '%' . $keyword . '%');
                                        $count++;
                                    }
                                }
                            })
                            ->first();
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
                        
                        // Nếu người dùng KHÔNG nhắc đến "pro" hoặc "max" thì ưu tiên bản thường
                        if (! Str::contains($normalized, ['pro', 'max', 'fold', 'flip'])) {
                            $product = $products->first(function (Product $p) {
                                $name = Str::lower($p->name);
                                return ! Str::contains($name, ['pro', 'max']);
                            }) ?? $products->first();
                        } else {
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

        // Nhận diện dung lượng cụ thể mà người dùng đang hỏi (128GB, 256GB, ...)
        $requestedStorage = null;
        if (preg_match('/\b(\d{2,4}\s*gb)\b/i', $message, $matchStorage)) {
            $requestedStorage = strtoupper(preg_replace('/\s+/', '', $matchStorage[1])); // "128GB"
        }

        $storages = $product->variants
            ->map(function ($v) {
                if ($v->storage) {
                    return $v->storage->storage;
                }

                if ($v->description && preg_match('/\b(\d{2,4}\s*gb)\b/i', $v->description, $match)) {
                    return strtoupper(preg_replace('/\s+/', '', $match[1]));
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
        $variantsForSummary = $product->variants;
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

        if (preg_match('/\b(\d{5,9})\b/', preg_replace('/[^\d]/', ' ', $message), $match)) {
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
        $query = Product::query()->with('category');

        if (!empty($filters['category_keyword'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['category_keyword'] . '%');
            });
        }

        if (!empty($filters['keyword']) && empty($filters['category_keyword'])) {
            // Chỉ lọc theo tên khi không có gợi ý category rõ ràng
            $query->where('name', 'like', '%' . $filters['keyword'] . '%');
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        $products = $query->orderByDesc('views')->limit(5)->get();

        // Chỉ fallback về sản phẩm phổ biến khi người dùng không nêu loại sản phẩm cụ thể
        if ($products->isEmpty() && empty($filters['keyword']) && empty($filters['category_keyword']) && empty($filters['min_price']) && empty($filters['max_price'])) {
            $products = Product::orderByDesc('views')->limit(5)->get();
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
            return "- {$product->name}: " . number_format($product->price, 0, ',', '.') . "đ (slug: {$product->slug})";
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

    protected function generateAnswer(string $message, string $context, bool $isCouponOnly = false): string
    {
        $apiKey = config('services.openai.key');
        $model = config('services.openai.model', 'gpt-4o-mini');

        if ($apiKey) {
            $systemPrompt = 'Bạn là trợ lý bán hàng của một cửa hàng điện tử, hãy tư vấn ngắn gọn, thân thiện bằng tiếng Việt và chỉ sử dụng thông tin trong phần context.';
            
            if ($isCouponOnly) {
                $systemPrompt .= ' Khi người dùng hỏi về mã khuyến mãi của họ, hãy chỉ trả lời về mã khuyến mãi, không đề cập đến sản phẩm.';
            }
            
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
        }

        return $this->fallbackResponse($context);
    }

    protected function fallbackResponse(string $context): string
    {
        return "Hiện tại tôi đề xuất một vài lựa chọn nổi bật:\n{$context}\nBạn có thể cho tôi biết thêm nhu cầu cụ thể để mình tư vấn chính xác hơn nhé!";
    }
}

