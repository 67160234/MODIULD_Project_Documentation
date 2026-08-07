# MODIULD Project Overview

## Vision

MODIULD คือแพลตฟอร์มสำหรับสร้าง Workflow โดยไม่ต้องใช้ Excel ผู้ใช้สามารถสร้าง
Workspace, Loadout และเลือก Module เพื่อประกอบเป็นระบบงานของตนเอง

## Core Concepts

-   Workspace
-   Loadout
-   Module
-   Dashboard
-   AI Recommendation

## ตัวอย่าง Loadout

Hospital - ระบบจองห้องผ่าตัด - ติดตามเตียง - จัดคิว - จัดการยา

## Frontend

-   Login/Register
-   Workspace
-   Loadout Manager
-   Module Marketplace
-   Dashboard (Drag & Drop)
-   Settings

## Backend

Services: - Auth - User - Workspace - Loadout - Module - Dashboard -
Workflow - AI - Notification

REST API + JWT

## Database

MySQL (Docker)

ตารางหลัก: - users - workspaces - loadouts - modules - dashboards -
widgets - workflows - workflow_steps

## AI

-   แนะนำ Module
-   ค้นหา Module ด้วยภาษาธรรมชาติ
-   สร้าง Loadout อัตโนมัติ (อนาคต)

## Docker Stack

-   Nginx
-   PHP-FPM
-   MySQL
-   phpMyAdmin
-   Redis (Optional)

## Suggested Structure

    modiuld/
    ├── frontend/
    ├── backend/
    ├── database/
    ├── docker/
    ├── docs/
    └── docker-compose.yml

## Roadmap

v0.1 Login, Dashboard, Loadout v0.2 Workflow, Permission v0.3 AI
Recommendation v0.4 Marketplace v1.0 Production
