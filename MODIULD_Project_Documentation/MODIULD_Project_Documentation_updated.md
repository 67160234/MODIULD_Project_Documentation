# MODIULD Project Documentation
## Implementation Specification for Antigravity

> This document is the implementation specification for MODIULD.  
> An AI coding agent such as Antigravity must use this document together with the existing source code, `Login.pdf`, and the supplied image assets.
>
> Requirements marked **MUST** are acceptance requirements, not suggestions.

---

# 1. Project Overview

## 1.1 Product

**MODIULD** is a modular workflow platform that lets users build their own work system by selecting modules and combining them into a **Loadout**.

The project is intended to reduce dependence on manually managed Excel workflows.

## 1.2 Core Concepts

- **Guest** — can use the system without an account, but can create only **1 Loadout**
- **User Account** — registered/logged-in users can create **unlimited Loadouts**
- **Loadout** — a customized workspace containing selected modules
- **Module** — an individual functional component that can be added to a Loadout
- **Workspace** — the working area of a Loadout
- **AI Recommendation** — future/placeholder functionality

---

# 2. Existing Technology and Architecture

Repository:

`https://github.com/67160234/MODIULD_Project_Documentation`

The existing project uses:

- Frontend: HTML / CSS / JavaScript
- Backend: PHP
- Database: MySQL
- Deployment: Docker / Docker Compose
- Web server: Nginx
- PHP runtime: PHP-FPM
- Authentication: JWT
- phpMyAdmin

Existing structure:

```text
modiuld/
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
├── backend/
│   ├── src/
│   │   ├── index.php
│   │   ├── config/
│   │   ├── middleware/
│   │   ├── models/
│   │   ├── controllers/
│   │   └── routes/
│   ├── composer.json
│   └── Dockerfile
├── database/
│   └── init.sql
├── docker/
│   └── nginx/
└── docker-compose.yml
```

### Important

**Do not replace the existing architecture unnecessarily.**

First inspect the current code, then reuse the existing:

- authentication
- API routing
- database connection
- Docker configuration
- CSS/design system
- frontend structure

Only extend or modify what is necessary.

---

# 3. Reference UI — MUST MATCH THE PDF

The supplied file:

`Login.pdf`

is the **primary visual source of truth** for the frontend.

Supplied assets:

- `icon1.png`
- `Moduild_logo.png`

## 3.1 Exact Visual Requirement

The frontend MUST follow the PDF as closely as possible.

Match:

- layout
- spacing
- alignment
- typography
- button placement
- navigation
- cards
- borders
- border radius
- gradients
- colors
- icons
- logo placement
- menus
- Loadout layout
- module layout
- language indicator
- Light/Dark controls
- responsive behavior

**Do not redesign the interface into a different style.**

If an existing CSS implementation differs from the PDF, update it to match the PDF.

The supplied PDF is more important than an old generic style rule.

---

# 4. Required Pages

The application must support these pages/flows:

1. Landing / Home
2. Login
3. Register
4. Loadouts
5. Create New Loadout
6. Loadout Workspace
7. Account/Menu
8. Settings / Color Mode

Reference flow:

```text
Landing / Home
      │
      ├── Sign in
      │      └── Login
      │             └── Loadouts
      │
      ├── Register
      │      └── Register
      │             └── Login / Loadouts
      │
      └── Guest
             └── Loadouts
                    └── Create 1 Loadout maximum
```

---

# 5. Authentication — REAL FUNCTIONALITY REQUIRED

The following MUST use the real backend and MySQL:

- Register
- Login
- Logout

These are NOT mockups.

## 5.1 Register

Register must:

- accept user information
- validate required fields
- validate email
- prevent duplicate account
- validate password
- validate password confirmation
- store the user in MySQL
- hash passwords
- never store plaintext passwords

Existing password policy:

- minimum 8 characters
- at least 1 uppercase letter
- at least 1 number

## 5.2 Login

Login must:

1. authenticate against MySQL
2. obtain/use JWT
3. store/use authentication state according to the existing architecture
4. identify the logged-in user
5. load that user's Loadouts
6. allow unlimited Loadout creation

Invalid credentials must show an error.

## 5.3 Logout

Logout must:

1. call the real logout behavior
2. invalidate/remove authentication state
3. clear frontend authentication state/token as appropriate
4. return the user to the public/landing page
5. prevent continued access to authenticated functionality

A visual-only logout button is NOT acceptable.

---

# 6. Guest Mode

Guest mode must work without registration.

## 6.1 Guest Limit

A Guest can create/use **ONLY ONE Loadout**.

```text
Guest
 └── Loadout 1
```

After one Loadout exists, another Create Loadout attempt MUST be blocked.

Show a clear message, for example:

> Guest accounts can create only 1 Loadout. Please register or sign in to create more.

The message must use the existing MODIULD visual style.

## 6.2 Guest Session

Guest state may use session/local/browser storage as appropriate.

The Guest does not need a permanent database account.

The Guest must still be able to:

- enter the system
- create one Loadout
- select modules
- open the Loadout
- use the modules

## 6.3 Logged-in Users

A logged-in user has **no artificial Loadout limit**.

```text
User
 ├── Loadout 1
 ├── Loadout 2
 ├── Loadout 3
 ├── Loadout 4
 └── ...
```

---

# 7. Loadout System

Loadout is a core feature and MUST work.

## 7.1 Loadout List

The Loadouts page must visually match the PDF.

It includes the concepts shown in the reference:

- `+ Create New Loadout`
- `Your Loadout`
- Loadout items/cards
- Loadout selection
- menu/account controls

## 7.2 Create New Loadout

The Create Loadout page must contain:

- Loadout Name
- Description
- module categories
- module selection
- Reset
- Create

The PDF reference shows text/concepts including:

```text
Loadout Untitled

Enter a description of your task,
such as 'Booking details' or 'Patient records'.

Room Booking
Asset Tracking
Seat Allocation

Space and Resources

Personnel and Teams

Profile Management
Queueing
Team Schedule
Work Tracking

Reset
Create
```

Use the PDF to determine the exact visual arrangement.

## 7.3 Create Logic

When Create is clicked:

1. validate Loadout name
2. create the Loadout
3. associate selected modules
4. persist it when the user is authenticated
5. show it in the Loadout list
6. allow opening it

For Guest, enforce the 1-Loadout limit.

## 7.4 Ownership

A logged-in user can access only their own Loadouts.

Backend authorization MUST verify ownership.

Do not trust a client-provided `user_id`.

---

# 8. Seven Required Modules

There are exactly **7 required modules** for this version.

## Category A — Space and Resources

### 8.1 Room Booking

Purpose: manage room reservations.

Prototype functionality MUST include:

- room list
- booking list
- create booking
- select room
- date/time
- booking status
- view existing bookings
- basic conflict prevention where practical

---

### 8.2 Asset Tracking

Purpose: track assets/resources.

Prototype functionality MUST include:

- asset list
- asset ID/code
- asset name
- status
- location
- search/filter
- add/edit/delete interaction where practical

---

### 8.3 Seat Allocation

Purpose: allocate seats/spaces.

Prototype functionality MUST include:

- available seats
- occupied seats
- select seat
- assign seat
- reassign seat
- release/clear seat
- current allocation display

---

## Category B — Personnel and Teams

### 8.4 Profile Management

Purpose: manage personnel profiles.

Prototype functionality MUST include:

- profile display
- name
- email/contact where appropriate
- role/position
- edit
- save
- cancel

Where possible, logged-in account information should be shown using real user data.

---

### 8.5 Queueing

Purpose: manage queues.

Prototype functionality MUST include:

- queue list
- queue number
- person/customer/patient identifier
- status
- add to queue
- call/advance next
- complete/remove queue item

---

### 8.6 Team Schedule

Purpose: manage team schedules.

Prototype functionality MUST include:

- schedule display
- date
- time
- person/team
- task/event
- add
- edit
- delete/cancel

---

### 8.7 Work Tracking

Purpose: track tasks/work.

Prototype functionality MUST include:

- task list
- task title
- assignee
- status
- priority where appropriate
- progress
- create task
- update status
- complete task

---

# 9. Real vs Mockup Scope

Not every future feature needs a production backend.

## 9.1 MUST BE REAL

These must work with real backend/database logic:

- Register
- Login
- Logout
- JWT authentication
- `/api/me`
- MySQL user data
- authenticated Loadouts
- Loadout ownership
- Guest 1-Loadout restriction

## 9.2 MUST BE FUNCTIONAL PROTOTYPES

All 7 modules must be clickable and usable.

A module is considered functional as a prototype when:

- it opens
- its UI is complete enough to use
- buttons perform their intended prototype action
- forms accept input
- data can be displayed
- state changes are visible
- user can return to the Loadout

If module persistence is not implemented yet, use mock/in-memory/local state.

**Do not leave module buttons as dead buttons.**

## 9.3 MAY REMAIN MOCKUPS

The following may remain mockups:

- AI Recommendation
- natural-language Module Search
- automatic Loadout generation
- Module Marketplace
- advanced workflow automation
- external integrations
- advanced analytics

A mockup should look like part of the product and have basic interaction, but it does not need a production AI/backend implementation.

---

# 10. Loadout Workspace

The Loadout workspace must display selected modules.

The reference PDF shows module items with:

- module name
- View
- Edit

Required interaction:

```text
Loadout
 ├── Module A
 │     ├── View
 │     └── Edit
 ├── Module B
 │     ├── View
 │     └── Edit
 └── ...
```

## View

Opens the module's working interface.

## Edit

Opens the module configuration/edit interface.

Both must work.

---

# 11. Account Menu

The menu must follow the PDF.

The reference includes:

- Account
- Switch account
- Logout
- Setting
- Color Mode
- Light Mode
- Dark Mode

## 11.1 Logout

Must use the real authentication logout.

## 11.2 Setting

May initially be a functional prototype.

## 11.3 Color Mode

Light/Dark mode should work if shown in the current reference.

---

# 12. Language / Text

The PDF contains English and Thai UI text.

Use the wording shown in the PDF for corresponding UI elements.

Do not arbitrarily replace the UI with unrelated translations.

The `EN` indicator should remain visually consistent with the reference.

Full i18n is not required unless already implemented.

---

# 13. Supplied Assets

Use:

```text
icon1.png
Moduild_logo.png
```

Do not replace these with another logo.

If needed, place them under:

```text
frontend/assets/
├── icon1.png
└── Moduild_logo.png
```

Use the existing project asset structure if it already provides the correct location.

---

# 14. Database

Database: **MySQL**

Deployment: **Docker**

The existing Docker Compose setup must continue to work.

At minimum, the application needs logical support for:

```text
users
loadouts
modules
loadout_modules
```

Recommended relationship:

```text
users
  │
  └──< loadouts
          │
          └──< loadout_modules >── modules
```

Additional tables are allowed for the 7 modules if needed.

## 14.1 Database Rules

- Preserve existing authentication tables.
- Do not unnecessarily delete existing data structures.
- Update `database/init.sql` when schema changes are required.
- Docker database initialization must remain reproducible.

---

# 15. API

Reuse the existing API architecture.

Existing authentication endpoints:

```text
POST /api/register
POST /api/login
POST /api/logout
POST /api/change-password
GET  /api/me
```

Add endpoints only when needed.

Recommended Loadout API:

```text
GET    /api/loadouts
POST   /api/loadouts
GET    /api/loadouts/{id}
PUT    /api/loadouts/{id}
DELETE /api/loadouts/{id}
```

Recommended module API:

```text
GET    /api/modules
GET    /api/loadouts/{id}/modules
POST   /api/loadouts/{id}/modules
DELETE /api/loadouts/{id}/modules/{moduleId}
```

If the existing project already uses different routes, keep the existing route conventions rather than duplicating functionality.

---

# 16. Authorization and Security

Authorization MUST be enforced on the backend.

Do not rely on frontend button hiding.

Protected API requests must validate JWT.

For Loadouts:

```text
Authenticated User
        │
        └── only own Loadouts
```

Guest:

```text
Guest
  └── maximum 1 Loadout
```

The backend must determine the current user from the authenticated token.

---

# 17. Frontend Design

The existing project design direction includes:

- dark mode
- purple/blue gradient
- glassmorphism cards
- Inter font
- fade-in
- slide-up
- blob pulse

Preserve these where they match the PDF.

**PDF visual reference has priority.**

Do not introduce a completely new design system.

---

# 18. Responsive Design

The application must remain usable on:

- desktop
- laptop
- tablet
- mobile width

Responsive changes must preserve the PDF's visual hierarchy.

Do not unnecessarily alter the desktop layout.

---

# 19. Error Handling

Implement visible, readable errors.

Examples:

### Login

```text
Invalid email or password.
```

### Duplicate registration

```text
An account with this email already exists.
```

### Guest limit

```text
Guest accounts can create only 1 Loadout.
Please register or sign in to create more.
```

### Unauthorized

Redirect to Login/Home as appropriate.

### Network/API failure

Show a useful error instead of silently failing.

---

# 20. Existing Authentication Must Not Be Broken

Before modifying authentication, inspect:

- `backend/src/controllers/`
- `backend/src/middleware/`
- `backend/src/routes/`
- `frontend/js/auth.js`
- `database/init.sql`
- Docker configuration

Then test:

1. Register
2. Login
3. `/api/me`
4. Logout
5. protected access

Reuse working authentication code instead of replacing it with a frontend-only implementation.

---

# 21. Recommended Implementation Order

## Phase 1 — Inspect

Before coding:

- inspect the complete repository
- inspect frontend
- inspect backend
- inspect database
- inspect Docker
- inspect authentication
- inspect all supplied assets
- inspect `Login.pdf`

Do not rewrite the application blindly.

## Phase 2 — Reference UI

Implement/match:

1. Landing/Home
2. Login
3. Register
4. Loadouts
5. Create New Loadout
6. Loadout Workspace
7. Account/Menu
8. Settings/Color Mode

Use the PDF as the visual reference.

## Phase 3 — Authentication

Verify:

- Register
- Login
- JWT
- `/api/me`
- Logout
- protected routes

## Phase 4 — Loadouts

Implement:

- create
- list
- open
- ownership
- module selection
- unlimited authenticated Loadouts
- Guest 1-Loadout restriction

## Phase 5 — Seven Modules

Implement:

1. Room Booking
2. Asset Tracking
3. Seat Allocation
4. Profile Management
5. Queueing
6. Team Schedule
7. Work Tracking

## Phase 6 — Future Mockups

Keep advanced unfinished functionality as mockups:

- AI Recommendation
- Marketplace
- automatic Loadout generation
- advanced workflow automation

## Phase 7 — Test

Run the full acceptance checklist.

---

# 22. Acceptance Checklist

## Authentication

- [ ] Register creates a real MySQL user
- [ ] Register validates fields
- [ ] Duplicate registration is rejected
- [ ] Login works with real credentials
- [ ] JWT is used
- [ ] `/api/me` identifies the user
- [ ] Logout works
- [ ] Protected pages/API reject unauthenticated access

## Guest

- [ ] Guest can enter without registering
- [ ] Guest can create 1 Loadout
- [ ] Guest can open/use that Loadout
- [ ] Guest cannot create a second Loadout
- [ ] Guest receives a clear limit message
- [ ] Login/Register gives the user unlimited Loadouts

## Logged-in User

- [ ] User can create Loadout
- [ ] User can create second Loadout
- [ ] User can create many Loadouts
- [ ] No 1-Loadout limit applies to authenticated users
- [ ] User sees own Loadouts
- [ ] User cannot access another user's Loadout

## Loadout

- [ ] Loadout list matches PDF
- [ ] Create Loadout matches PDF
- [ ] Name works
- [ ] Description works
- [ ] Module selection works
- [ ] Create works
- [ ] Reset works
- [ ] Created Loadout appears in list
- [ ] Loadout opens
- [ ] Selected modules appear in workspace

## Seven Modules

- [ ] Room Booking opens and works as a prototype
- [ ] Asset Tracking opens and works as a prototype
- [ ] Seat Allocation opens and works as a prototype
- [ ] Profile Management opens and works as a prototype
- [ ] Queueing opens and works as a prototype
- [ ] Team Schedule opens and works as a prototype
- [ ] Work Tracking opens and works as a prototype

## UI

- [ ] Supplied logo is used
- [ ] Supplied icon is used
- [ ] Login visually matches PDF
- [ ] Register visually matches PDF
- [ ] Loadout page visually matches PDF
- [ ] Create Loadout visually matches PDF
- [ ] Workspace visually matches PDF
- [ ] Account menu matches PDF
- [ ] Light/Dark behavior works where shown
- [ ] Responsive layout works
- [ ] No broken images
- [ ] No dead buttons
- [ ] No obvious console errors

## Docker

- [ ] `docker-compose up -d --build` works
- [ ] Frontend works
- [ ] Backend works
- [ ] MySQL works
- [ ] phpMyAdmin works
- [ ] API health works
- [ ] Database initializes correctly

---

# 23. Definition of Done

The project is complete when:

1. Frontend visually matches the supplied PDF as closely as possible.
2. Supplied MODIULD logo/icon assets are used.
3. Register works with MySQL.
4. Login works with JWT.
5. Logout works.
6. Guest can create exactly 1 Loadout.
7. Authenticated users can create unlimited Loadouts.
8. Loadout ownership is enforced by the backend.
9. All 7 modules are accessible and usable as functional prototypes.
10. Unfinished advanced features are represented as mockups.
11. Docker starts the application.
12. Existing working authentication is not unnecessarily broken.
13. The acceptance checklist passes.

---

# 24. Final Expected User Experience

## Guest

```text
Open MODIULD
    ↓
Guest
    ↓
Your Loadout
    ↓
Create New Loadout
    ↓
Select modules
    ↓
Create
    ↓
Loadout Workspace
    ↓
Use modules
```

Second Loadout attempt:

```text
Create New Loadout
        ↓
BLOCK
        ↓
Guest accounts can create only 1 Loadout.
Please register or sign in to create more.
```

## Registered / Logged-in User

```text
Open MODIULD
    ↓
Sign In
    ↓
Your Loadouts
    ↓
Create New Loadout
    ↓
Select modules
    ↓
Create
    ↓
Loadout Workspace
```

The user can repeat:

```text
Create Loadout
Create Loadout
Create Loadout
...
```

with no artificial Loadout limit.

---

# 25. Reference Files

Use these references:

```text
MODIULD_Project_Documentation.md
Login.pdf
icon1.png
Moduild_logo.png
```

The PDF and supplied images are visual references.

The existing repository source code is the implementation base.

When an existing implementation conflicts with this specification, satisfy this specification while preserving compatible existing functionality.

---

# 26. Completion Report Required from the AI Agent

After implementation, provide a concise report containing:

1. files changed
2. database changes
3. API changes
4. frontend pages changed
5. authentication status
6. Guest restriction status
7. Loadout status
8. status of all 7 modules
9. remaining mockup features
10. Docker/test result
11. remaining known limitations

Do not report the task as complete until the acceptance checklist has been tested.
