# Progress 60%

# MODIULD Project

> **แพลตฟอร์มสำหรับสร้าง Workflow และระบบจัดการงานของตัวเอง โดยไม่ต้องพึ่ง Excel**

MODIULD คือแพลตฟอร์มสำหรับสร้างและจัดการ Workflow ที่ผู้ใช้สามารถเลือกฟังก์ชันที่ต้องการมาใช้งานในรูปแบบ **Loadout** โดยไม่จำเป็นต้องสร้างระบบใหม่ตั้งแต่ต้น เหมาะสำหรับองค์กร ทีมงาน ห้องปฏิบัติการ สำนักงาน โรงพยาบาล หรือหน่วยงานที่ต้องการระบบจัดการงานเฉพาะของตัวเอง

แนวคิดหลักของ MODIULD คือ

**เลือก → สร้าง Loadout → เพิ่ม Module → ใช้งาน Workflow → จัดการข้อมูล**

---

## 📌 Project Overview

MODIULD ถูกออกแบบมาเพื่อแก้ปัญหาการจัดการข้อมูลและ Workflow ที่มักกระจายอยู่ใน Excel, Spreadsheet หรือระบบหลายตัวที่แยกออกจากกัน

แทนที่จะให้ผู้ใช้สร้างระบบใหม่ทั้งหมด MODIULD จะให้ผู้ใช้เลือก Module ที่ต้องการแล้วนำมาประกอบเป็น **Loadout** ของตัวเอง

ตัวอย่างเช่น

* โรงพยาบาลสามารถสร้าง Loadout สำหรับจัดการห้อง
* ห้องปฏิบัติการสามารถสร้าง Loadout สำหรับจัดการที่นั่งและตารางทีม
* บริษัทสามารถสร้าง Loadout สำหรับติดตามทรัพย์สินและงาน
* ทีมงานสามารถสร้าง Loadout สำหรับจัดการ Queue และ Work Tracking

---

# 🎯 Project Goals

MODIULD มีเป้าหมายหลักดังนี้

1. ลดการพึ่งพา Excel ในการจัดการ Workflow
2. ให้ผู้ใช้สร้างระบบที่เหมาะกับงานของตัวเอง
3. ให้เลือกเฉพาะ Module ที่ต้องการใช้งาน
4. ให้ผู้ใช้สามารถมีหลาย Loadout ได้
5. แยกสิทธิ์การใช้งานระหว่าง Guest และ Registered / Logged-in User
6. มีระบบ Authentication ที่ใช้งานจริง
7. รองรับการต่อยอด Module ในอนาคต
8. ให้ Frontend สามารถใช้งานตาม Design / PDF Specification
9. ทำให้ระบบสามารถพัฒนาและ Deploy ด้วย Docker ได้
10. เตรียมโครงสร้างให้สามารถเพิ่ม AI Assistant สำหรับช่วยเลือก Module หรือออกแบบ Workflow ได้ในอนาคต

---

# 👤 User Types

MODIULD แบ่งผู้ใช้ออกเป็น 2 กลุ่มหลัก

## 1. Guest User

ผู้ใช้ที่ยังไม่ได้สมัครสมาชิกหรือ Login

Guest สามารถ

* เข้า Landing Page
* ดูรายละเอียดของ MODIULD
* ทดลองใช้งานระบบ
* สร้าง Loadout ได้สูงสุด **1 Loadout**
* เลือก Module เพื่อทดลองใช้งาน
* ทดลอง Workflow ที่ระบบเปิดให้ใช้งาน

ข้อจำกัดของ Guest:

```text
Guest
 └── Maximum 1 Loadout
```

หากต้องการสร้าง Loadout เพิ่ม จะต้องสมัครสมาชิกและ Login

---

## 2. Registered / Logged-in User

ผู้ใช้ที่สมัครสมาชิกและ Login สำเร็จ

สามารถ

* สร้าง Loadout ได้ไม่จำกัด
* แก้ไข Loadout
* ลบ Loadout
* เลือก Module
* ใช้งาน Module
* จัดการข้อมูลของตัวเอง
* ดูข้อมูล Profile
* Logout
* เปลี่ยน Password
* ใช้งาน Workflow ของแต่ละ Loadout

โครงสร้างโดยรวม:

```text
Registered / Logged-in User
│
├── Profile
│
├── Loadout 1
│   ├── Module A
│   ├── Module B
│   └── Module C
│
├── Loadout 2
│   ├── Module A
│   └── Module D
│
├── Loadout 3
│   └── ...
│
└── Unlimited Loadouts
```

---

# 🧭 User Journey

## Guest User Journey

```text
Landing Page
     │
     ▼
Explore MODIULD
     │
     ▼
เลือกทดลองใช้งาน
     │
     ▼
สร้าง Loadout
     │
     ▼
เลือก Module
     │
     ▼
ใช้งาน Module
     │
     ├───────────────┐
     │               │
     ▼               ▼
ใช้งานต่อ        ต้องการ Loadout เพิ่ม
                     │
                     ▼
                Register
                     │
                     ▼
                   Login
                     │
                     ▼
             Registered User
```

Guest สามารถทดลองระบบได้โดยไม่ต้องสมัครสมาชิก แต่จำกัดไว้ที่ **1 Loadout**

---

## Registered / Logged-in User Journey

```text
Landing Page
     │
     ▼
Register
     │
     ▼
Login
     │
     ▼
Dashboard
     │
     ▼
Create Loadout
     │
     ▼
Select Modules
     │
     ▼
Configure Loadout
     │
     ▼
Open Loadout
     │
     ▼
Use Modules
     │
     ├── Room Booking
     ├── Asset Tracking
     ├── Seat Allocation
     ├── Profile Management
     ├── Queueing
     ├── Team Schedule
     └── Work Tracking
     │
     ▼
Manage / Edit / Delete
     │
     ▼
Create another Loadout
```

Registered User สามารถสร้าง Loadout ได้ไม่จำกัดตามการออกแบบของระบบ

---

# 🔄 MODIULD Core Flow

Core Flow ของระบบแบ่งออกเป็นขั้นตอนหลักดังนี้

```text
USER
 │
 ▼
Authentication
 │
 ├── Guest
 │      │
 │      └── Maximum 1 Loadout
 │
 └── Registered / Logged-in
        │
        └── Unlimited Loadouts
                 │
                 ▼
             Dashboard
                 │
                 ▼
            Create Loadout
                 │
                 ▼
          Select Modules
                 │
                 ▼
          Configure Loadout
                 │
                 ▼
             Open Loadout
                 │
                 ▼
           Use Workflow
                 │
                 ▼
          Manage Data
```

---

# 📦 Loadout System

**Loadout** คือชุดของ Module ที่ผู้ใช้เลือกมาประกอบเป็นระบบสำหรับงานหนึ่ง ๆ

ตัวอย่าง:

```text
Loadout: Laboratory Management
│
├── Seat Allocation
├── Team Schedule
├── Asset Tracking
└── Work Tracking
```

อีกตัวอย่าง:

```text
Loadout: Hospital Management
│
├── Room Booking
├── Queueing
├── Asset Tracking
└── Work Tracking
```

Loadout ช่วยให้ผู้ใช้สามารถสร้างระบบเฉพาะของตัวเองโดยไม่จำเป็นต้องเริ่มจากระบบเปล่า

---

# 🧩 Available Modules

MODIULD มี Module หลักทั้งหมด **7 Modules**

## 1. Room Booking

ใช้สำหรับจัดการการจองห้อง

ตัวอย่างการใช้งาน:

* จองห้อง
* ตรวจสอบห้องว่าง
* ดูตารางการจอง
* จัดการข้อมูลห้อง
* ตรวจสอบสถานะห้อง

---

## 2. Asset Tracking

ใช้สำหรับจัดการและติดตามทรัพย์สิน

ตัวอย่าง:

* รายการทรัพย์สิน
* Asset ID
* ชื่อทรัพย์สิน
* สถานะ
* ตำแหน่ง
* ผู้รับผิดชอบ

---

## 3. Seat Allocation

ใช้สำหรับจัดการการจัดสรรที่นั่ง

ตัวอย่าง:

* รายการที่นั่ง
* ผู้ใช้งาน
* ตำแหน่งที่นั่ง
* สถานะที่นั่ง
* การจัดสรรที่นั่ง

---

## 4. Profile Management

ใช้สำหรับจัดการข้อมูลผู้ใช้

ตัวอย่าง:

* Username
* Email
* Full Name
* Password
* Profile Information

---

## 5. Queueing

ใช้สำหรับจัดการระบบคิว

ตัวอย่าง:

* สร้างคิว
* ดูคิวปัจจุบัน
* จัดลำดับคิว
* เปลี่ยนสถานะคิว
* เรียกคิวถัดไป

---

## 6. Team Schedule

ใช้สำหรับจัดการตารางเวลาของทีม

ตัวอย่าง:

* ตารางงาน
* ตารางสมาชิก
* Schedule
* วันและเวลา
* การจัดสรรงาน

---

## 7. Work Tracking

ใช้สำหรับติดตามงาน

ตัวอย่าง:

* สร้างงาน
* Assign งาน
* กำหนดสถานะ
* ติดตาม Progress
* ตรวจสอบงานที่เสร็จแล้ว

---

# 🖥️ Frontend

Frontend ของ MODIULD ถูกออกแบบให้สอดคล้องกับ PDF / Design Specification ของโครงการ

Frontend ประกอบด้วยส่วนหลักดังนี้

```text
Frontend
│
├── Landing Page
│
├── Register
│
├── Login
│
├── Dashboard
│
├── Loadout Management
│
├── Module Selection
│
├── Module Pages
│
├── Profile
│
└── Logout
```

Frontend เน้น

* Dark UI
* Purple / Blue Gradient
* Glassmorphism
* Card-based Layout
* Responsive Layout
* Animation
* Modern Dashboard
* Consistent Design System

---

# 🎨 Frontend Design

Design หลักของระบบ

* Dark Mode
* Purple / Blue Gradient
* Glassmorphism Cards
* Inter Font
* Fade-in Animation
* Slide-up Animation
* Blob / Background Animation
* Modern Dashboard UI
* Custom Logo
* Custom Icons

Frontend ต้องพยายามให้ตรงกับ PDF / UI Specification ของโครงการ

---

# 🔐 Authentication

ระบบ Authentication ใช้งานจริงผ่าน Backend API

รองรับ

```text
Register
   │
   ▼
Login
   │
   ▼
JWT Token
   │
   ▼
Authenticated User
```

ระบบมี

* Register
* Login
* Logout
* JWT Authentication
* Password Change
* Get Current User
* Username Checking
* Token Blacklist

---

# 🔑 Password Policy

Password ต้องมีอย่างน้อย

* 8 ตัวอักษร
* ตัวพิมพ์ใหญ่อย่างน้อย 1 ตัว
* ตัวเลขอย่างน้อย 1 ตัว

ตัวอย่าง Password ที่ผ่าน:

```text
Test1234!
```

---

# 🌐 API

## Authentication API

| Method | Endpoint               | Auth | Status | Description        |
| ------ | ---------------------- | ---- | ------ | ------------------ |
| POST   | `/api/register`        | ❌    | ✅ Real | สมัครสมาชิก        |
| POST   | `/api/login`           | ❌    | ✅ Real | Login และสร้าง JWT |
| POST   | `/api/logout`          | ✅    | ✅ Real | Logout             |
| POST   | `/api/change-password` | ✅    | ✅ Real | เปลี่ยน Password   |

---

## User API

| Method | Endpoint                         | Auth | Status           | Description             |
| ------ | -------------------------------- | ---- | ---------------- | ----------------------- |
| GET    | `/api/me`                        | ✅    | ✅ Real           | ดึงข้อมูลผู้ใช้ปัจจุบัน |
| GET    | `/api/check-username/{username}` | ❌    | ✅ Real           | ตรวจสอบ Username        |
| GET    | `/api/users`                     | ✅    | Prototype / Mock | รายการผู้ใช้            |
| GET    | `/api/users/{id}`                | ✅    | Prototype / Mock | ข้อมูลผู้ใช้            |
| PUT    | `/api/users/{id}`                | ✅    | Prototype / Mock | แก้ไขผู้ใช้             |
| DELETE | `/api/users/{id}`                | ✅    | Prototype / Mock | ลบผู้ใช้                |

---

# 🧪 Implemented / Prototype / Future

เพื่อให้สถานะของระบบชัดเจน ระบบแบ่ง Feature เป็น 3 ระดับ

## ✅ Implemented

ฟังก์ชันที่เชื่อม Backend และ Database แล้ว

* Register
* Login
* Logout
* JWT Authentication
* Password Change
* Get Current User
* Username Checking
* MySQL Database
* Docker Environment

---

## 🟡 Prototype / Mockup

ฟังก์ชันที่มี UI และ Flow สำหรับทดลองใช้งาน แต่ Backend บางส่วนยังไม่ได้เชื่อมต่อแบบสมบูรณ์

* Loadout Dashboard
* Loadout Selection
* Module Selection
* Room Booking
* Asset Tracking
* Seat Allocation
* Queueing
* Team Schedule
* Work Tracking
* User Management บางส่วน

**Prototype / Mockup ไม่ได้หมายความว่าไม่มี UI หรือไม่มี Flow แต่หมายถึง Backend / Database ของฟังก์ชันนั้นยังอยู่ในระหว่างการพัฒนา**

---

## 🔮 Future Development

ฟังก์ชันที่สามารถพัฒนาต่อในอนาคต

* AI Assistant
* AI แนะนำ Module
* AI แนะนำ Workflow
* Advanced Permission System
* Team / Organization Management
* Real-time Notification
* Advanced Analytics
* Custom Module Builder
* Workflow Automation
* Integration กับ External Services

---

# 🤖 AI Assistant Concept

ในอนาคต MODIULD สามารถเพิ่ม AI Assistant เพื่อช่วยผู้ใช้สร้างระบบ

ตัวอย่าง:

```text
User:
"ฉันต้องการระบบสำหรับจัดการห้องประชุม
และตารางทีม"

          ↓

AI Assistant

          ↓

แนะนำ Modules

Room Booking
Team Schedule

          ↓

สร้าง Loadout

Meeting Management
```

AI สามารถช่วย

* วิเคราะห์ความต้องการ
* แนะนำ Module
* แนะนำ Workflow
* แนะนำ Loadout
* Filter Module
* ช่วยตั้งชื่อ Loadout
* ช่วยอธิบายการใช้งาน Module

---

# 🏗️ System Architecture

โครงสร้างระบบโดยรวม

```text
                    USER
                      │
                      ▼
                ┌───────────┐
                │ Frontend  │
                │ HTML/CSS/ │
                │ JavaScript│
                └─────┬─────┘
                      │
                      │ HTTP / REST API
                      ▼
                ┌───────────┐
                │   Nginx   │
                └─────┬─────┘
                      │
                      ▼
                ┌───────────┐
                │   PHP     │
                │  Backend  │
                └─────┬─────┘
                      │
                      ▼
                ┌───────────┐
                │   MySQL   │
                │ Database  │
                └───────────┘
```

ทั้งหมดทำงานผ่าน Docker Environment

---

# 🐳 Docker

MODIULD ใช้ Docker เพื่อจัดการ Environment ของระบบ

ส่วนประกอบหลัก

```text
Docker
│
├── Nginx
│
├── PHP Backend
│
├── MySQL
│
└── phpMyAdmin
```

---

# ⚠️ สำคัญมาก: ต้องใช้ Linux Containers / Linux Warehouse

MODIULD ต้องทำงานบน **Linux Containers**

ถ้าใช้ Docker Desktop บน Windows ให้ตรวจสอบว่า Docker กำลังใช้ **Linux Containers** ไม่ใช่ Windows Containers

แนวคิดคือ

```text
Windows Host
      │
      ▼
Docker Desktop
      │
      ▼
Linux Containers
      │
      ├── Nginx
      ├── PHP
      ├── MySQL
      └── phpMyAdmin
```

**ไม่ควรใช้ Windows Containers สำหรับ Environment ของ MODIULD**

---

# 🔄 วิธีเปลี่ยน Docker จาก Windows Containers เป็น Linux Containers

## วิธีที่ 1: ผ่าน Docker Desktop

1. เปิด Docker Desktop
2. ไปที่บริเวณ **System Tray** ด้านขวาล่างของ Windows
3. คลิกขวาที่ไอคอน Docker
4. ถ้าพบตัวเลือก

```text
Switch to Linux containers...
```

ให้กดตัวเลือกนี้

5. Docker จะถามเพื่อยืนยัน
6. กด **Switch**
7. รอ Docker Desktop Restart
8. เมื่อ Docker กลับมาทำงาน ให้เปิด Terminal ใหม่

จากนั้นกลับไปที่โฟลเดอร์โปรเจกต์และรัน

```bash
docker compose up -d --build
```

หรือถ้าเครื่องใช้คำสั่งแบบเก่า

```bash
docker-compose up -d --build
```

---

## วิธีตรวจสอบว่าเป็น Linux Containers แล้วหรือยัง

เปิด Terminal แล้วใช้

```bash
docker info
```

ตรวจสอบข้อมูล Docker Engine

จากนั้นสามารถตรวจสอบ Container ที่กำลังทำงานได้ด้วย

```bash
docker ps
```

ถ้า Container ของ MODIULD สามารถ Start ได้ตามปกติ แสดงว่า Environment พร้อมใช้งาน

---

# 🪟 Windows Host ≠ Windows Container

สำหรับผู้ที่พัฒนา MODIULD บน Windows ต้องเข้าใจว่า

```text
Windows
   │
   └── Docker Desktop
           │
           └── Linux Containers
                   │
                   ├── Nginx
                   ├── PHP
                   ├── MySQL
                   └── phpMyAdmin
```

การใช้ Windows เป็นเครื่อง Host **ยังสามารถใช้ได้**

สิ่งที่ต้องเปลี่ยนคือ Docker Container ให้เป็น Linux Containers

ดังนั้น

```text
❌ Windows Host + Windows Containers

✅ Windows Host + Linux Containers
```

---

# 🚀 Quick Start

## 1. Clone Repository

```bash
git clone https://github.com/67160234/MODIULD_Project_Documentation.git
```

เข้าโฟลเดอร์โปรเจกต์

```bash
cd MODIULD_Project_Documentation
```

---

## 2. เข้าโฟลเดอร์ Application

```bash
cd MODIULD_Project_Documentation/modiuld
```

---

## 3. ตรวจสอบ Docker

```bash
docker --version
```

และ

```bash
docker compose version
```

---

## 4. ตรวจสอบ Linux Containers

หากใช้ Docker Desktop บน Windows ต้องใช้

```text
Linux Containers
```

ไม่ใช่

```text
Windows Containers
```

---

## 5. Build และ Start

แนะนำให้ใช้

```bash
docker compose up -d --build
```

หากเครื่องใช้ Docker Compose รุ่นเก่า

```bash
docker-compose up -d --build
```

---

## 6. ตรวจสอบ Container

```bash
docker ps
```

ควรเห็น Container ที่เกี่ยวข้องกับระบบ เช่น

```text
nginx
php
mysql
phpmyadmin
```

ชื่อ Container อาจแตกต่างกันตาม `docker-compose.yml`

---

# 🌐 Access

เมื่อ Docker ทำงานแล้ว สามารถเข้าใช้งานได้ที่

| Service    | URL                         |
| ---------- | --------------------------- |
| Frontend   | http://localhost            |
| phpMyAdmin | http://localhost:8080       |
| API Health | http://localhost/api/health |

---

# 🗄️ Database

ระบบใช้ **MySQL**

Database ถูกเตรียมผ่าน

```text
database/init.sql
```

โดย Docker จะทำหน้าที่สร้าง Database และ Initial Data ตาม Configuration ของโปรเจกต์

---

# 🔑 Default Database Credentials

ค่า Default ที่ใช้ใน Development Environment

```text
Root User
Username: root
Password: root_password
```

```text
Application User
Username: modiuld_user
Password: modiuld_pass
```

```text
Database
modiuld_db
```

> สำหรับ Production ควรเปลี่ยน Password และ Credentials จากค่า Default

---

# 🧰 phpMyAdmin

สามารถจัดการ MySQL ผ่าน phpMyAdmin ได้ที่

```text
http://localhost:8080
```

ใช้สำหรับ

* ดู Database
* ดู Tables
* ตรวจสอบข้อมูล
* Query SQL
* Debug Database
* ตรวจสอบ User Data

---

# 📁 Project Structure

```text
MODIULD_Project_Documentation/
│
├── README.md
│
├── MODIULD_Project_Documentation/
│   ├── MODIULD_Project_Documentation.md
│   ├── pic/
│   └── modiuld/
│
└── modiuld/
    │
    ├── frontend/
    │   ├── index.html
    │   ├── login.html
    │   ├── register.html
    │   ├── dashboard.html
    │   ├── css/
    │   │   └── style.css
    │   ├── js/
    │   │   └── auth.js
    │   └── assets/
    │
    ├── backend/
    │   ├── src/
    │   │   ├── index.php
    │   │   ├── config/
    │   │   ├── middleware/
    │   │   ├── models/
    │   │   ├── controllers/
    │   │   └── routes/
    │   │       └── api.php
    │   │
    │   ├── composer.json
    │   └── Dockerfile
    │
    ├── database/
    │   └── init.sql
    │
    ├── docker/
    │   └── nginx/
    │
    └── docker-compose.yml
```

---

# 🔌 API Usage Examples

## Register

```bash
curl -X POST http://localhost/api/register \
  -H "Content-Type: application/json" \
  -d '{"username":"johndoe","email":"john@example.com","password":"Test1234!","full_name":"John Doe"}'
```

---

## Login

```bash
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"Test1234!"}'
```

เมื่อ Login สำเร็จ Backend จะส่ง JWT Token กลับมา

ตัวอย่างการใช้งาน Token:

```bash
curl http://localhost/api/me \
  -H "Authorization: Bearer <YOUR_TOKEN>"
```

---

## Get Current User

```bash
curl http://localhost/api/me \
  -H "Authorization: Bearer <YOUR_TOKEN>"
```

---

## Check Username

```bash
curl http://localhost/api/check-username/johndoe
```

---

# 🔒 JWT Authentication

ระบบใช้ JWT สำหรับ Authentication

Flow:

```text
Login
  │
  ▼
Backend ตรวจสอบ Email / Password
  │
  ▼
สร้าง JWT
  │
  ▼
Frontend เก็บ Token
  │
  ▼
ส่ง Token ใน Authorization Header
  │
  ▼
Backend ตรวจสอบ Token
  │
  ▼
อนุญาตให้เข้าถึง Protected API
```

รูปแบบ Header:

```http
Authorization: Bearer <YOUR_TOKEN>
```

---

# 🚪 Logout

เมื่อผู้ใช้ Logout

```text
User
 │
 ▼
Logout
 │
 ▼
JWT ถูกยกเลิกการใช้งาน
 │
 ▼
Token Blacklist
 │
 ▼
User กลับไปสถานะ Guest
```

ระบบใช้ Database Table สำหรับ Token Blacklist

---

# ⏱️ JWT Expiration

JWT มีอายุ

```text
24 ชั่วโมง
```

หลังจากหมดอายุ ผู้ใช้จำเป็นต้อง Login ใหม่

---

# 🧪 Development Flow

ลำดับการพัฒนาที่แนะนำ

```text
1. Start Docker
       │
       ▼
2. Check MySQL
       │
       ▼
3. Check Backend API
       │
       ▼
4. Check Frontend
       │
       ▼
5. Test Register
       │
       ▼
6. Test Login
       │
       ▼
7. Test JWT
       │
       ▼
8. Test Dashboard
       │
       ▼
9. Test Loadout
       │
       ▼
10. Test Modules
       │
       ▼
11. Test Logout
```

---

# 🧪 Testing Checklist

## Authentication

* [ ] Register สำเร็จ
* [ ] Register ด้วย Username ซ้ำไม่ได้
* [ ] Register ด้วย Email ซ้ำไม่ได้
* [ ] Password Policy ทำงาน
* [ ] Login สำเร็จ
* [ ] Login ด้วย Password ผิดไม่สำเร็จ
* [ ] JWT ถูกสร้าง
* [ ] Protected API ตรวจสอบ JWT
* [ ] Logout ทำงาน
* [ ] Token Blacklist ทำงาน
* [ ] Password Change ทำงาน

---

## Guest

* [ ] Guest เข้า Landing Page ได้
* [ ] Guest สามารถทดลองระบบ
* [ ] Guest สร้าง Loadout ได้
* [ ] Guest จำกัด 1 Loadout
* [ ] Guest ไม่สามารถสร้าง Loadout ที่ 2 ได้
* [ ] Guest สามารถ Register เพื่อปลดข้อจำกัด

---

## Registered User

* [ ] Login สำเร็จ
* [ ] เข้า Dashboard ได้
* [ ] สร้าง Loadout ได้
* [ ] สร้างหลาย Loadout ได้
* [ ] เลือก Module ได้
* [ ] เปิด Loadout ได้
* [ ] แก้ไข Loadout ได้
* [ ] ลบ Loadout ได้
* [ ] Logout ได้

---

## Modules

* [ ] Room Booking
* [ ] Asset Tracking
* [ ] Seat Allocation
* [ ] Profile Management
* [ ] Queueing
* [ ] Team Schedule
* [ ] Work Tracking

ฟังก์ชันที่ยังไม่มี Backend สมบูรณ์ให้ถือเป็น **Prototype / Mockup** จนกว่าจะเชื่อม Database และ API จริง

---

# 📊 Feature Status

| Feature               | Status                 |
| --------------------- | ---------------------- |
| Landing Page          | ✅ Implemented          |
| Register              | ✅ Real                 |
| Login                 | ✅ Real                 |
| Logout                | ✅ Real                 |
| JWT                   | ✅ Real                 |
| Password Change       | ✅ Real                 |
| Profile               | 🟡 Prototype / Partial |
| Guest Loadout         | 🟡 Prototype           |
| Registered Loadout    | 🟡 Prototype           |
| Loadout Management    | 🟡 Prototype           |
| Module Selection      | 🟡 Prototype           |
| Room Booking          | 🟡 Prototype           |
| Asset Tracking        | 🟡 Prototype           |
| Seat Allocation       | 🟡 Prototype           |
| Profile Management    | 🟡 Prototype           |
| Queueing              | 🟡 Prototype           |
| Team Schedule         | 🟡 Prototype           |
| Work Tracking         | 🟡 Prototype           |
| AI Assistant          | 🔮 Future              |
| Custom Module Builder | 🔮 Future              |
| Advanced Automation   | 🔮 Future              |

---

# 📝 Important Notes

## Google Login

Google Login เป็น Mockup ในเวอร์ชันปัจจุบัน

```text
Google Login
     │
     └── UI มีแล้ว
         แต่ยังไม่ได้เชื่อม Google OAuth จริง
```

---

## Backend Status

ไม่ใช่ทุก Module ที่มี Backend จริงในเวอร์ชันปัจจุบัน

ดังนั้นต้องแยกให้ชัดเจนระหว่าง

```text
Implemented
Prototype / Mockup
Future
```

ห้ามถือว่า Prototype / Mockup เป็นระบบ Backend ที่เสร็จสมบูรณ์แล้ว

---

# 🧑‍💻 Development Rules

เมื่อเพิ่ม Feature ใหม่ควรทำตาม Flow

```text
Requirement
    │
    ▼
UI Design
    │
    ▼
Frontend
    │
    ▼
API
    │
    ▼
Backend
    │
    ▼
Database
    │
    ▼
Testing
```

ถ้า Feature ยังไม่มี Backend ให้ระบุสถานะเป็น

```text
Prototype / Mockup
```

---

# 🧱 Development Architecture

การพัฒนา MODIULD แบ่งออกเป็น

```text
Frontend
   │
   │ REST API
   ▼
Backend
   │
   │ SQL
   ▼
MySQL
```

Docker ทำหน้าที่เป็น Environment สำหรับให้แต่ละ Service ทำงานร่วมกัน

---

# 🐳 Docker Development Commands

## Start

```bash
docker compose up -d
```

## Build ใหม่

```bash
docker compose up -d --build
```

## ดู Container

```bash
docker ps
```

## ดู Logs

```bash
docker compose logs
```

## ดู Logs แบบ Real-time

```bash
docker compose logs -f
```

## Stop

```bash
docker compose down
```

## Stop และลบ Volume

```bash
docker compose down -v
```

> คำสั่ง `docker compose down -v` จะลบ Docker Volumes ที่เกี่ยวข้อง ดังนั้นข้อมูล Database ที่อยู่ใน Volume อาจถูกลบ

---

# 🛠️ Troubleshooting

## Docker ใช้ Windows Containers

หากระบบ Build หรือ Start ไม่ได้ ให้ตรวจสอบ Docker Desktop ก่อน

ต้องเปลี่ยนเป็น

```text
Linux Containers
```

โดย

```text
Docker Desktop
→ System Tray
→ Right Click Docker
→ Switch to Linux containers...
→ Switch
```

จากนั้น

```bash
docker compose down
docker compose up -d --build
```

---

## Container ไม่ทำงาน

ตรวจสอบ

```bash
docker ps
```

และ

```bash
docker compose logs
```

---

## Database ไม่ทำงาน

ตรวจสอบ

```bash
docker compose logs mysql
```

และตรวจสอบว่า MySQL Container ทำงานอยู่

```bash
docker ps
```

---

## API ไม่ทำงาน

ตรวจสอบ

```text
http://localhost/api/health
```

ถ้าไม่สามารถเข้าถึงได้ ให้ตรวจสอบ

```bash
docker compose logs
```

โดยเฉพาะ Nginx และ Backend

---

# 📌 Definition of Done

Feature หนึ่งจะถือว่าเสร็จสมบูรณ์เมื่อ

```text
Requirement
     │
     ▼
Frontend
     │
     ▼
Backend API
     │
     ▼
Database
     │
     ▼
Authentication / Authorization
     │
     ▼
Validation
     │
     ▼
Error Handling
     │
     ▼
Testing
     │
     ▼
Done
```

ถ้ามีเพียง UI แต่ยังไม่มี Backend / Database ให้ระบุว่า

```text
Prototype / Mockup
```

---

# 🚀 Future Roadmap

## Phase 1 — Foundation

* Authentication
* JWT
* User Management
* MySQL
* Docker
* Frontend Foundation

## Phase 2 — Loadout

* Loadout Creation
* Loadout Management
* Module Selection
* Guest Limitation
* Registered User Unlimited Loadouts

## Phase 3 — Modules

* Room Booking
* Asset Tracking
* Seat Allocation
* Profile Management
* Queueing
* Team Schedule
* Work Tracking

## Phase 4 — Backend Integration

* Module APIs
* Database Integration
* Validation
* Permission System
* Real-time Data

## Phase 5 — AI

* AI Module Recommendation
* AI Workflow Recommendation
* AI Loadout Assistant
* Natural Language Workflow Creation

---

# 📚 Project Concept Summary

MODIULD ไม่ได้ถูกออกแบบมาให้เป็นเพียงระบบ CRUD ทั่วไป แต่เป็น Platform ที่ให้ผู้ใช้เลือกความสามารถที่ต้องการและนำมาประกอบเป็นระบบของตัวเอง

แนวคิดหลักคือ

```text
Traditional System

User
 │
 ▼
ระบบสำเร็จรูป
 │
 └── ต้องใช้ Feature ที่ระบบมี
```

เปลี่ยนเป็น

```text
MODIULD

User
 │
 ▼
เลือก Module
 │
 ▼
สร้าง Loadout
 │
 ▼
ประกอบ Workflow
 │
 ▼
ได้ระบบของตัวเอง
```

---

# 🎯 MODIULD Core Concept

```text
                 MODIULD
                    │
        ┌───────────┴───────────┐
        │                       │
       User                  Loadout
        │                       │
        │                ┌──────┴──────┐
        │                │             │
        │             Module         Module
        │                │             │
        │                └──────┬──────┘
        │                       │
        └───────────────────────┘
                    │
                    ▼
                 Workflow
                    │
                    ▼
              User's Own System
```

---

# 📌 Current Version Summary

เวอร์ชันปัจจุบันของ MODIULD มีแนวคิดและโครงสร้างดังนี้

* มี Landing Page
* มี Register / Login / Logout แบบ Real
* ใช้ JWT Authentication
* มี Guest User
* Guest สร้างได้สูงสุด 1 Loadout
* Registered / Logged-in User สร้าง Loadout ได้ไม่จำกัด
* มี Loadout Concept
* มี Module Selection
* มี 7 Modules
* Frontend พัฒนาตาม Design / PDF Specification
* Module ที่ยังไม่มี Backend ให้ถือเป็น Prototype / Mockup
* ใช้ MySQL
* ใช้ Docker
* Docker ต้องทำงานด้วย Linux Containers
* มี phpMyAdmin
* มี REST API
* มี Token Blacklist
* Google Login ยังเป็น Mock
* มีโครงสร้างรองรับการพัฒนา AI Assistant ในอนาคต

---

# 👥 User Flow Summary

```text
                         MODIULD
                            │
                 ┌──────────┴──────────┐
                 │                     │
              GUEST                REGISTERED
                 │                     │
                 ▼                     ▼
           ทดลองใช้งาน               Login
                 │                     │
                 ▼                     ▼
          Create Loadout           Dashboard
                 │                     │
                 ▼                     ▼
           Select Module         Create Loadout
                 │                     │
                 ▼                     ▼
          Use Workflow           Select Modules
                 │                     │
                 │                     ▼
                 │                Use Workflow
                 │                     │
                 │                     ▼
                 │              Create More Loadouts
                 │                     │
                 └──────────┬──────────┘
                            ▼
                       MODIULD Platform
```

---

# 📄 Documentation

เอกสารเพิ่มเติมของโครงการอยู่ภายใน Repository

```text
MODIULD_Project_Documentation/
```

รายละเอียดเกี่ยวกับ Concept, Design และ Project Documentation สามารถดูได้จากเอกสารภายใน Repository

---

# 👨‍💻 Project

**MODIULD Project**

แพลตฟอร์มสำหรับสร้าง Workflow และระบบจัดการงานของตัวเอง โดยไม่ต้องพึ่ง Excel

Repository:

https://github.com/67160234/MODIULD_Project_Documentation
