# MODIULD Project

แพลตฟอร์มสำหรับสร้าง Workflow โดยไม่ต้องใช้ Excel

## 🚀 Quick Start

### Requirements
- Docker Desktop
- Docker Compose

### Run

```bash
cd modiuld
docker-compose up -d --build
```
### ต้องใช้ Linux containers

1.ไปที่มุมขวาล่างของหน้าจอ (System Tray)
2.คลิกขวาที่ไอคอน Docker (รูปปลาวาฬ)
3.คลิกเลือก Switch to Linux containers...
4.จะมีหน้าต่างป๊อปอัปขึ้นมาให้ยืนยัน ให้กด Switch
5.รอให้ Docker รีสตาร์ทสักครู่ แล้วลองรันคำสั่ง Docker ใหม่อีกครั้ง

### Access
| Service       | URL                        |
|---------------|----------------------------|
| Frontend      | http://localhost            |
| phpMyAdmin    | http://localhost:8080       |
| API Health    | http://localhost/api/health |

---

## 📁 Project Structure

```
modiuld/
├── frontend/               ← HTML/CSS/JS frontend
│   ├── index.html          ← Landing page
│   ├── login.html          ← Login page (real API)
│   ├── register.html       ← Register page (real API)
│   ├── dashboard.html      ← Loadout dashboard (mock)
│   ├── css/style.css       ← Design system
│   ├── js/auth.js          ← Auth utilities
│   └── assets/             ← Logo, images
├── backend/
│   ├── src/
│   │   ├── index.php       ← Router entry point
│   │   ├── config/         ← DB + Config
│   │   ├── middleware/      ← JWT AuthMiddleware
│   │   ├── models/         ← User model
│   │   ├── controllers/    ← AuthController, UserController
│   │   └── routes/api.php  ← Route definitions
│   ├── composer.json
│   └── Dockerfile
├── database/init.sql       ← MySQL schema + seed data
├── docker/nginx/           ← Nginx config
└── docker-compose.yml
```

---

## 🔗 API Endpoints

### ✅ Authentication (Implemented)

| Method | Endpoint              | Auth | Description          |
|--------|-----------------------|------|----------------------|
| POST   | `/api/register`       | ❌   | สมัครสมาชิก           |
| POST   | `/api/login`          | ❌   | เข้าสู่ระบบ (JWT)     |
| POST   | `/api/logout`         | ✅   | ออกจากระบบ            |
| POST   | `/api/change-password`| ✅   | เปลี่ยนรหัสผ่าน       |

### ✅ User (Partially Real)

| Method | Endpoint                   | Auth | Description              |
|--------|----------------------------|------|--------------------------|
| GET    | `/api/me`                  | ✅   | ดึงข้อมูลตัวเอง (real)   |
| GET    | `/api/check-username/{n}`  | ❌   | ตรวจสอบ username (real)  |
| GET    | `/api/users`               | ✅   | รายการ user [MOCK]       |
| GET    | `/api/users/{id}`          | ✅   | ข้อมูล user [MOCK]       |
| PUT    | `/api/users/{id}`          | ✅   | แก้ไข user [MOCK]        |
| DELETE | `/api/users/{id}`          | ✅   | ลบ user [MOCK]           |

---

## 📋 API Usage Examples

### Register
```bash
curl -X POST http://localhost/api/register \
  -H "Content-Type: application/json" \
  -d '{"username":"johndoe","email":"john@example.com","password":"Test1234!","full_name":"John Doe"}'
```

### Login
```bash
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"Test1234!"}'
```

### Get Me (with token)
```bash
curl http://localhost/api/me \
  -H "Authorization: Bearer <YOUR_TOKEN>"
```

### Check Username
```bash
curl http://localhost/api/check-username/johndoe
```

---

## 🔐 Password Policy
- ขั้นต่ำ **8 ตัวอักษร**
- อย่างน้อย **1 ตัวพิมพ์ใหญ่** (A-Z)
- อย่างน้อย **1 ตัวเลข** (0-9)

## 🎨 Design
- Dark mode + Purple/Blue gradient
- Glassmorphism cards
- Font: Inter (Google Fonts)
- Animations: fade-in, slide-up, blob pulse

## 🗄️ Default DB Credentials
- **Root**: `root` / `root_password`
- **App user**: `modiuld_user` / `modiuld_pass`
- **Database**: `modiuld_db`

## 📝 Notes
- Google Login = Mock (ปุ่มมีแต่ยังไม่เชื่อม OAuth)
- JWT Expire = 24 ชั่วโมง
- Token blacklist ใช้ DB table (`token_blacklist`)
