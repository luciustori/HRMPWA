# ROADMAP 2.0: HRIS & Attendance System Transformation

**Philosophy:**
* **Desktop (Web):** "The Workspace". Focus on Management, Tasks, Requests, and Productivity.
* **Mobile (PWA/APK):** "The Remote". Focus on Speed, Attendance (GPS/Selfie), and Quick Approvals.
* **Approach:** "Pecah Telur" (Foundation First -> User Experience -> Mobile Adaptation).

---

## 🚀 PHASE 1: The Brain & Gatekeeper (Admin Web)
**Goal:** Establish Role-Based Access Control (RBAC) and Dynamic Navigation.

### 1.1. Role & Permission Module
* **Database:** Use existing `roles`, `permissions`, `role_permissions` structure.
* **UI Concept:** "Bento Grid Cards" (Not Tables).
    * **Grouping:** Permissions grouped by Module (e.g., "Employee Mgmt", "Finance", "Approval").
    * **Interaction:** Toggle Switches (ON/OFF) + "Select All" per group.
    * **Visual:** Modern Clean SaaS (Plus Jakarta Sans).
* **Logic:**
    * Map `permission_id` to Frontend Groups.
    * CRUD Roles (Create/Edit/Delete Roles).

### 1.2. Dynamic Sidebar
* **Logic:** Sidebar menu items rendered based on active User Permissions.
* **State:** If Permission OFF -> Menu Hidden (or Locked with Tooltip).

---

## 💻 PHASE 2: The Heart / Me ZONE (Desktop Web)
**Goal:** Create the daily workspace for all employees.

### 2.1. Dashboard Layout (Full Width)
* **Left Widget (Identity):** Profile Card + Attendance Status (Static Info).
    * *Note:* **NO CLOCK-IN BUTTON** on Desktop Dashboard (Encourage Mobile usage).
* **Center Widget (Productivity):** Task Management Board.
    * Tabs: To Do / In Progress / History.
    * Action: Detailed Input (Text + File Upload) for Task Submission.
* **Right Widget (Quick Info):** Leave Quota, Inbox/Announcements, Last Request Status.

### 2.2. Logic Implementations
* **Task Flow:** Full Detail View (Desktop optimized).
* **Request:** Detailed Forms for Leave/Overtime.
* **Auth:** Standard Web Login.

---

## 📱 PHASE 3: The Remote / PWA & APK (Mobile)
**Goal:** Fast, Secure, and "Elderly-Proof" Attendance.

### 3.1. Mobile UI/UX (Modern SaaS)
* **Style:** Bento Grid + Soft Glassmorphism + Floating Dock Navigation.
* **Interaction:** "Slide to Clock In" (Haptic Feedback).
* **Top Nav:** User Profile + "Admin Tools" Icon (Only triggers if user has specific Management Permissions).

### 3.2. Attendance Module (The "Visual Trap")
* **Security Layer 1:** Radius Lock (GPS match Office Location).
* **Security Layer 2:** Selfie Verification (Direct Camera Only).
    * **Overlay:** "HUD Style" Head & Shoulder silhouette to force wide angle.
    * **Logic:** Gyroscope Check (Must be Portrait & Upright).
    * **Compression:** Client-side Resize (800px width) + Convert to WebP.
    * **Maintenance:** Cron Job to auto-delete photos > 60 days.

### 3.3. Conversion to APK (Capacitor)
* **Framework:** Ionic Capacitor (wrapping HTML/CSS/JS).
* **The "Permission Gate":**
    * On App Start: Check GPS & Camera Permissions via Native Bridge.
    * If OFF: Show Full-Screen Blocking UI -> Button opens Android Settings directly.
* **Mock Location:** Detect and block "Fake GPS" users.

---

## 📊 PHASE 4: The Rearview Mirror (Reporting)
**Goal:** Data visualization.
* **Status:** Low Priority (Execute after Data Population from Phase 2 & 3).
* **Features:** Export Excel, Monthly Recap, Attendance Heatmap.