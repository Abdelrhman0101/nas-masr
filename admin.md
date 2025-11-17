# Admin API

- Base: `/api/admin`
- Auth: `Authorization: Bearer <token>` ويجب أن يكون `role=admin`
- Content-Type: `application/json`

## Category Fields
- GET `/api/category-fields?category_slug=cars` (عام، للعرض فقط)
  - Query: `category_slug` اختياري لتصفية النتائج
  - 200:
    ```json
    { "data": [ { "id": 1, "category_slug": "cars", "field_name": "model", "display_name": "الموديل", "type": "string", "options": [], "required": true, "filterable": true, "rules_json": {}, "sort_order": 1, "is_active": true } ] }
    ```

- POST `/api/admin/category-fields`
  - Body:
    ```json
    {
      "category_slug": "cars",
      "field_name": "model",
      "display_name": "الموديل",
      "type": "string",           // one of: string,int,decimal,bool,date,json
      "options": [],              // اختياري، مصفوفة (للـ dropdown)
      "required": true,
      "filterable": true,
      "rules_json": {},           // اختياري، مصفوفة
      "sort_order": 1,            // اختياري
      "is_active": true
    }
    ```
  - 201:
    ```json
    { "message": "تم إنشاء الحقل بنجاح", "data": { ...field } }
    ```

- PUT `/api/admin/category-fields/{categoryField}`
  - Body (اختياري لكل حقل):
    ```json
    {
      "category_slug": "cars",
      "field_name": "model",
      "display_name": "الموديل",
      "type": "string",
      "options": [],
      "required": true,
      "filterable": true,
      "rules_json": {},
      "sort_order": 1,
      "is_active": true
    }
    ```
  - 200:
    ```json
    { "message": "تم تحديث الحقل بنجاح", "data": { ...updatedField } }
    ```

- DELETE `/api/admin/category-fields/{categoryField}`
  - 200:
    ```json
    { "message": "تم إلغاء تفعيل الحقل" }
    ```

## Categories
- POST `/api/admin/categories`
  - Body:
    ```json
    { "slug": "cars", "name": "سيارات", "icon": "car.png", "is_active": true }
    ```
  - 201:
    ```json
    { "message": "تم إنشاء القسم بنجاح", "data": { ...category } }
    ```

- PUT `/api/admin/categories/{category}`
  - Body:
    ```json
    { "slug": "cars", "name": "سيارات", "icon": "car.png", "is_active": true }
    ```
  - 200:
    ```json
    { "message": "تم تحديث القسم بنجاح", "data": { ...category } }
    ```

- DELETE `/api/admin/categories/{category}`
  - 200:
    ```json
    { "message": "تم تعطيل القسم" }
    ```

## Admin Stats
- GET `/api/admin/stats`
  - 200:
    ```json
    {
      "cards": {
        "rejected": { "count": 3, "percent": -10.5, "direction": "down" },
        "pending":  { "count": 5, "percent": 8.5,   "direction": "up" },
        "active":   { "count": 120, "percent": 2.5, "direction": "up" },
        "total":    { "count": 128, "percent": 1.0, "direction": "up" }
      },
      "periods": {
        "current_month": { "start": "2025-11-01", "end": "2025-11-30" },
        "previous_month": { "start": "2025-10-01", "end": "2025-10-31" }
      }
    }
    ```

- GET `/api/admin/recent-activities?limit=20`
  - 200:
    ```json
    {
      "count": 20,
      "activities": [
        { "type": "listing_approved", "message": "تم تفعيل إعلان", "entity": "listing", "id": 10, "status": "Valid", "admin_approved": true, "timestamp": "2025-11-17T12:00:00Z", "ago": "قبل دقيقة" },
        { "type": "settings_updated", "message": "تم تحديث الإعدادات", "entity": "system_settings", "id": 3, "timestamp": "2025-11-17T11:59:00Z", "ago": "قبل دقيقتين" }
      ]
    }
    ```

- GET `/api/admin/users-summary?per_page=20&role=admin&status=active&q=010`
  - 200:
    ```json
    {
      "meta": { "page": 1, "per_page": 20, "total": 123, "last_page": 7 },
      "users": [
        { "id": 1, "name": "Admin", "phone": "01000000000", "user_code": "1", "status": "active", "registered_at": "2025-11-01", "listings_count": 5, "role": "admin" }
      ]
    }
    ```

## Users Management
- GET `/api/admin/users/{user}?per_page=20&status=Valid&all=false`
  - 200 (paginate):
    ```json
    {
      "user": { "id": 2, "name": "Ali", "phone": "010...", "user_code": "2", "status": "active", "registered_at": "2025-11-10", "listings_count": 3, "role": "user" },
      "listings": [
        { "id": 100, "title": "سيارة بحالة ممتازة", "image": "url.jpg", "section": "سيارات", "status": "منشور", "published_at": "2025-11-12" }
      ],
      "meta": { "page": 1, "per_page": 20, "total": 3, "last_page": 1 }
    }
    ```
  - إذا `all=true` بدون `per_page`: ترجع كل العناصر بنفس هيكل `listings` مع `meta.total`.

- POST `/api/admin/users`
  - Body:
    ```json
    {
      "name": "Ahmed",              // اختياري
      "phone": "01012345678",       // إجباري وفريد
      "role": "user",               // اختياري: user/advertiser/admin/reviewer
      "status": "active",           // اختياري: active/blocked
      "referral_code": "ABC123",    // اختياري
      "password": "123456"          // اختياري، افتراضي "123456" إن لم يُرسل
    }
    ```
  - 201:
    ```json
    { "message": "User created successfully", "user": { ...summary } }
    ```

- PUT `/api/admin/users/{user}`
  - Body (اختياري لكل حقل):
    ```json
    { "name": "Ahmed", "phone": "01099999999", "role": "advertiser", "status": "blocked", "referral_code": "XYZ" }
    ```
  - 200:
    ```json
    { "message": "User updated successfully", "user": { ...summary } }
    ```

- DELETE `/api/admin/users/{user}`
  - 200:
    ```json
    { "message": "User deleted successfully" }
    ```

- PATCH `/api/admin/users/{user}/block`
  - 200:
    ```json
    { "message": "User blocked successfully." }
    ```
  - إذا كان محظور مسبقًا:
    ```json
    { "message": "User unblocked successfully." }
    ```

## Notes
- كل مسارات الأدمن تتطلب `auth:sanctum` + `admin` (صلاحيات أدمن).
- الحقول `options` و`rules_json` تُقبل كسلاسل JSON وسيتم تحويلها لمصفوفات عند التحقق إذا لزم.