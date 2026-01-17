# Dislikes API Documentation

## Overview
هذا الـ API يسمح للمستخدمين بعمل dislike للمحتوى (Posts, Comments, Lawyers).

## Authentication
يتطلب authentication باستخدام Sanctum token في الـ header:
```
Authorization: Bearer {token}
```

## Base URL
```
/api/v1/dislikes
```

---

## Endpoint

### Toggle Dislike
عمل أو إلغاء dislike لعنصر معين (post, comment, lawyer)

**Endpoint:** `POST /api/v1/dislikes/toggle`

**Request Body:**
```json
{
    "type": "lawyer",
    "id": 5
}
```

**Parameters:**
- `type` (required): نوع العنصر - يجب أن يكون أحد القيم التالية:
  - `post` - منشور
  - `comment` - تعليق
  - `lawyer` - محامي
- `id` (required): معرف العنصر (integer)

**Response (Dislike Added):**
```json
{
    "success": true,
    "message": "تم عدم الإعجاب بنجاح",
    "data": {
        "disliked": true,
        "dislikes_count": 5
    }
}
```

**Response (Dislike Removed):**
```json
{
    "success": true,
    "message": "تم إلغاء عدم الإعجاب بنجاح",
    "data": {
        "disliked": false,
        "dislikes_count": 4
    }
}
```

**Error Response (Not Found):**
```json
{
    "success": false,
    "message": "غير موجود"
}
```

**Error Response (Validation Error):**
```json
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "type": [
            "The selected type is invalid."
        ],
        "id": [
            "The id field is required."
        ]
    }
}
```

---

## Usage Examples

### Example 1: Dislike a Lawyer
```javascript
fetch('/api/v1/dislikes/toggle', {
    method: 'POST',
    headers: {
        'Authorization': 'Bearer YOUR_TOKEN',
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        type: 'lawyer',
        id: 15
    })
})
.then(response => response.json())
.then(data => {
    console.log(data);
    // { success: true, message: "تم عدم الإعجاب بنجاح", data: { disliked: true, dislikes_count: 5 } }
});
```

### Example 2: Dislike a Post
```javascript
fetch('/api/v1/dislikes/toggle', {
    method: 'POST',
    headers: {
        'Authorization': 'Bearer YOUR_TOKEN',
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        type: 'post',
        id: 123
    })
})
.then(response => response.json())
.then(data => {
    console.log(data);
});
```

### Example 3: Dislike a Comment
```javascript
fetch('/api/v1/dislikes/toggle', {
    method: 'POST',
    headers: {
        'Authorization': 'Bearer YOUR_TOKEN',
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        type: 'comment',
        id: 456
    })
})
.then(response => response.json())
.then(data => {
    console.log(data);
});
```

---

## Resource Updates

تم تحديث الـ Resources التالية لتشمل معلومات الـ dislikes:

### LawyerResource
```json
{
    "id": 5,
    "name": "أحمد محمد",
    "likes_count": 150,
    "is_liked_by_me": false,
    "dislikes_count": 10,
    "is_disliked_by_me": true,
    ...
}
```

### PostResource
```json
{
    "id": 123,
    "content": "محتوى المنشور",
    "likes_count": 50,
    "dislikes_count": 5,
    "is_liked": false,
    "is_disliked": true,
    ...
}
```

### CommentResource
```json
{
    "id": 456,
    "content": "محتوى التعليق",
    "likes_count": 20,
    "dislikes_count": 2,
    "is_liked": false,
    "is_disliked": true,
    ...
}
```

---

## Notes

- المستخدم لا يمكنه عمل like و dislike لنفس العنصر في نفس الوقت
- عند عمل dislike لعنصر تم عمل like له مسبقاً، يتم إلغاء الـ like تلقائياً (يجب تنفيذ هذا في الكود إذا لزم الأمر)
- الـ toggle يعني: إذا كان المستخدم قد عمل dislike مسبقاً، سيتم إلغاؤه. وإذا لم يكن قد عمل dislike، سيتم إضافته
- جميع الـ counts يتم حسابها في الوقت الفعلي

---

## Database Schema

### dislikes table
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key to users)
- dislikeable_id (bigint)
- dislikeable_type (string)
- created_at (timestamp)
- updated_at (timestamp)
- unique index on (user_id, dislikeable_id, dislikeable_type)
```

---

## Related Endpoints

- **Likes Toggle:** `POST /api/v1/likes/toggle`
- **Follow Toggle:** `POST /api/v1/follow/toggle`
