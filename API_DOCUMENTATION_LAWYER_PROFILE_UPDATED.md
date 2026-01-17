# Lawyer Profile API - Updated Documentation

## Overview
تم تحديث الـ Lawyer Profile API لتشمل إحصائيات التقييمات والمراجعات مباشرة في الـ response بدلاً من استدعاء endpoint منفصل.

## Changes Made

### 1. Removed Separate Statistics Endpoint
❌ **Old:** `GET /api/v1/reviews/{lawyer_id}/statistics/ratings`

✅ **New:** الإحصائيات مدمجة في `GET /api/v1/lawyer/{lawyer_id}`

### 2. Enhanced Lawyer Profile Response
الآن يتضمن الـ profile:
- إحصائيات التقييمات الكاملة
- آخر 10 مراجعات معتمدة
- معلومات العملاء الذين كتبوا المراجعات

---

## Endpoint

### Get Lawyer Profile
احصل على بيانات المحامي الكاملة مع الإحصائيات والمراجعات

**Endpoint:** `GET /api/v1/lawyer/{lawyer_id}`

**Authentication:** Required (Bearer Token)

**Response:**
```json
{
    "success": true,
    "message": "Success",
    "data": {
        "id": 5,
        "name": "أحمد محمد",
        "name_en": "Ahmed Mohamed",
        "name_ar": "أحمد محمد",
        "email": "ahmed@example.com",
        "user_type": "lawyer",
        "gender": "male",
        "phone": "01234567890",
        "phone_country": {
            "id": 1,
            "name": "Egypt",
            "code": "+20"
        },
        "address": "123 Main St, Cairo",
        "city": {
            "id": 1,
            "name": "Cairo"
        },
        "region": {
            "id": 1,
            "name": "Nasr City"
        },
        "consultation_price": 500.00,
        "active": true,
        "is_featured": true,
        "register_grade": {
            "id": 1,
            "name": "First Grade"
        },
        "section_of_law": [
            {
                "id": 1,
                "name": "Criminal Law"
            },
            {
                "id": 2,
                "name": "Civil Law"
            }
        ],
        "experience": "10 years of experience",
        "experience_en": "10 years of experience",
        "experience_ar": "10 سنوات خبرة",
        "latitude": "30.0444",
        "longitude": "31.2357",
        "officeHours": {
            "morning": [
                {
                    "day": "sunday",
                    "from": "09:00",
                    "to": "12:00"
                }
            ],
            "evening": [
                {
                    "day": "sunday",
                    "from": "17:00",
                    "to": "20:00"
                }
            ]
        },
        "profile_image": "https://example.com/storage/lawyers/profile.jpg",
        "id_card": "https://example.com/storage/lawyers/id_card.jpg",
        "facebook_url": "https://facebook.com/ahmed",
        "twitter_url": "https://twitter.com/ahmed",
        "instagram_url": "https://instagram.com/ahmed",
        "telegram_url": "https://t.me/ahmed",
        "tiktok_url": "https://tiktok.com/@ahmed",
        "fcm_token": "fcm_token_here",
        "subscription": {
            "id": 1,
            "name": "Premium Package",
            "number_of_months": 12
        },
        "subscription_expires_at": "2026-12-31",
        "followers_count": 150,
        "is_followed_by_me": true,
        "likes_count": 200,
        "is_liked_by_me": true,
        "dislikes_count": 10,
        "is_disliked_by_me": false,
        "average_rating": 4.5,
        "reviews_count": 50,
        
        // ✨ NEW: Rating Statistics
        "rating_statistics": {
            "average_rating": 4.52,
            "total_reviews": 50,
            "rating_distribution": {
                "5_stars": 30,
                "4_stars": 15,
                "3_stars": 3,
                "2_stars": 1,
                "1_star": 1
            }
        },
        
        // ✨ NEW: Latest Reviews (Last 10 approved)
        "reviews": [
            {
                "id": 123,
                "lawyer_id": 5,
                "customer_id": 45,
                "rating": 5,
                "comment": "محامي ممتاز وخدمة رائعة",
                "customer_name": "محمد علي",
                "customer_email": "mohamed@example.com",
                "approved": true,
                "created_at": "2026-01-15 10:30:00",
                "updated_at": "2026-01-15 10:30:00"
            },
            {
                "id": 122,
                "lawyer_id": 5,
                "customer_id": 44,
                "rating": 4,
                "comment": "خدمة جيدة",
                "customer_name": "أحمد حسن",
                "customer_email": "ahmed@example.com",
                "approved": true,
                "created_at": "2026-01-14 14:20:00",
                "updated_at": "2026-01-14 14:20:00"
            }
        ],
        
        "created_at": "2025-01-01 00:00:00",
        "updated_at": "2026-01-17 12:00:00"
    }
}
```

---

## What's Included Now

### Rating Statistics Object
```json
"rating_statistics": {
    "average_rating": 4.52,      // متوسط التقييم
    "total_reviews": 50,          // إجمالي عدد المراجعات
    "rating_distribution": {
        "5_stars": 30,            // عدد التقييمات 5 نجوم
        "4_stars": 15,            // عدد التقييمات 4 نجوم
        "3_stars": 3,             // عدد التقييمات 3 نجوم
        "2_stars": 1,             // عدد التقييمات 2 نجوم
        "1_star": 1               // عدد التقييمات 1 نجمة
    }
}
```

### Reviews Array
- يتم إرجاع آخر 10 مراجعات معتمدة فقط
- مرتبة من الأحدث للأقدم
- تتضمن معلومات العميل (الاسم والبريد الإلكتروني)
- فقط المراجعات المعتمدة (`approved = true`)

---

## Migration Guide

### Before (Old Way)
```javascript
// Step 1: Get lawyer profile
const lawyerResponse = await fetch('/api/v1/lawyer/5');
const lawyer = await lawyerResponse.json();

// Step 2: Get rating statistics (separate call)
const statsResponse = await fetch('/api/v1/reviews/5/statistics/ratings');
const stats = await statsResponse.json();

// Step 3: Get reviews (separate call)
const reviewsResponse = await fetch('/api/v1/reviews/5');
const reviews = await reviewsResponse.json();
```

### After (New Way)
```javascript
// Single call gets everything!
const response = await fetch('/api/v1/lawyer/5', {
    headers: {
        'Authorization': 'Bearer YOUR_TOKEN',
        'Accept': 'application/json'
    }
});

const data = await response.json();

// Access everything from one response
const lawyer = data.data;
const statistics = lawyer.rating_statistics;
const reviews = lawyer.reviews;

console.log(`Average Rating: ${statistics.average_rating}`);
console.log(`Total Reviews: ${statistics.total_reviews}`);
console.log(`5 Stars: ${statistics.rating_distribution['5_stars']}`);
console.log(`Latest Reviews:`, reviews);
```

---

## Benefits

✅ **Reduced API Calls:** من 3 requests إلى 1 request فقط

✅ **Better Performance:** تحميل أسرع للبيانات

✅ **Simpler Code:** كود أبسط وأسهل في الصيانة

✅ **Consistent Data:** جميع البيانات من نفس الوقت (no race conditions)

✅ **Mobile Friendly:** أقل استهلاك للبيانات والبطارية

---

## Notes

- الـ `rating_statistics` و `reviews` يتم إرجاعهم فقط عندما يتم تحميل الـ reviews relation
- إذا لم يكن هناك مراجعات، سيتم إرجاع:
  - `rating_statistics.average_rating = 0`
  - `rating_statistics.total_reviews = 0`
  - `reviews = []`
- المراجعات غير المعتمدة لا تظهر في الـ API
- يتم تحميل معلومات العميل تلقائياً مع كل مراجعة

---

## Error Responses

### 404 - Lawyer Not Found
```json
{
    "success": false,
    "message": "Lawyer not found"
}
```

### 401 - Unauthorized
```json
{
    "message": "Unauthenticated."
}
```
