# دليل استخدام صورة الأطباء الموحدة

تم تطبيق التعديلات لجعل صورة قسم الأطباء اختيارية، مثل قسم الوظائف، مع عرض صورة افتراضية موحدة.

## 1. رفع الصورة الافتراضية (عن طريق Postman)

لتعيين الصورة التي ستظهر لجميع الأطباء، استخدم الـ Endpoint التالي:

**الرابط:**
`POST {{base_url}}/api/admin/system-settings/upload-image`

**الـ Headers:**
- `Authorization`: `Bearer {{admin_token}}`
- `Accept`: `application/json`

**الـ Body (form-data):**
| Key | Type | Value | Description |
| --- | --- | --- | --- |
| `key` | Text | `doctors_default_image` | **ضروري جداً** |
| `image` | File | (اختار الصورة) | ملف الصورة (jpg, png, webp) |

---

## 2. التأكد من النتيجة

1.  بعد الرفع بنجاح، ستعود الـ API برابط الصورة الجديدة.
2.  افتح أي إعلان طبيب (قائمة أو تفاصيل) أو قائمة "أفضل المعلنين" للأطباء عبر:
    `GET {{base_url}}/api/v1/doctors/listings`
    أو
    `GET {{base_url}}/api/best-advertisers/doctors`
3.  ستجد أن حقل `main_image_url` يعرض الصورة التي قمت برفعها للتو.

## 3. ملاحظات

- عند إنشاء إعلان طبيب جديد (`POST /api/v1/doctors/listings`)، لا داعي لإرسال حقل `main_image` إلا إذا كنت تريد صورة مخصصة لهذا الطبيب تحديداً (وهذا ما زال مدعوماً كخيار).
- إذا لم يتم إرسال صورة، سيظهر الإعلان بالصورة الافتراضية.
