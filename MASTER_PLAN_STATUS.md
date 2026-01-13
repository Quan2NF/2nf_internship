# Master Plan - Project Management System (PMS)

## 📋 Tổng quan kiến trúc

| Thành phần | Trạng thái | Mô tả |
|------------|------------|-------|
| Laravel Sanctum + Restful API | ✅ | API authentication với Sanctum tokens |
| Repository + Service Layer | ✅ | Tách biệt logic nghiệp vụ và data access |
| DTO + Transformer (Spatie Data) | ✅ | 10 Data classes đã tạo |
| Policy + Gate | ✅ | 3 Policies cho User, Project, Issue |
| Exception Handling | ✅ | Custom exceptions + Handler |
| Event + Listener + Job | ✅ | 6 Events, 6 Listeners, 1 Job |
| Soft Delete | ✅ | User, Project, Issue đều có SoftDeletes |

---

## ✅ Chi tiết từng thành phần đã hoàn thành

### 1. Authentication (Laravel Sanctum + Restful API)
| API | Endpoint | Trạng thái |
|-----|----------|------------|
| API01_Login | POST /api/auth/login | ✅ Có sẵn |
| API02_Forgot Password | POST /api/auth/forgot-password | ✅ Có sẵn |
| API03_Reset Password | POST /api/auth/reset-password | ✅ Có sẵn |
| API04_Logout | POST /api/auth/logout | ✅ Có sẵn |

**Files:**
- `AuthController.php`
- `AuthService.php`
- `UserData.php` (Auth)

### 2. User Management (API05-API11)
| API | Endpoint | Trạng thái | Mô tả |
|-----|----------|------------|-------|
| API05_Users | GET /api/users | ✅ | List all users |
| API06_Filter Users | GET /api/users/filter | ✅ | Filter by name, email, is_active, role |
| API07_Create User | POST /api/users | ✅ | Create new user |
| API08_Edit User | PUT /api/users/{id} | ✅ | Update user info |
| API09_Delete User | DELETE /api/users/{id} | ✅ | Soft delete user |
| API10_Assign Role | POST /api/users/{id}/assign-role | ✅ | Assign role to user |
| API11_List Of User Role | GET /api/users/{id}/roles | ✅ | Get user's roles |

**Files:**
- `UserController.php`
- `User.php` (Model với SoftDeletes)
- `UserData.php`
- `UserDataCollection.php`
- `UserPolicy.php`

### 3. Role Management (API12-API15)
| API | Endpoint | Trạng thái | Mô tả |
|-----|----------|------------|-------|
| API12_List Of Roles | GET /api/roles | ✅ | List all roles |
| API13_Create New Role | POST /api/roles | ✅ | Create new role |
| API14_Edit Role | PUT /api/roles/{id} | ✅ | Update role |
| API15_Delete Role | DELETE /api/roles/{id} | ✅ | Delete role |

**Files:**
- `RoleController.php`
- `RoleData.php` ✅ **MỚI TẠO**
- `RoleDataCollection.php` ✅ **MỚI TẠO**

### 4. Project Management (API16-API22)
| API | Endpoint | Trạng thái | Mô tả |
|-----|----------|------------|-------|
| API16_List Of Projects | GET /api/projects | ✅ | List all projects |
| API17_Filter Project | GET /api/projects/filter | ✅ | Filter by name, status, pm_id |
| API18_Create New Project | POST /api/projects | ✅ | Create new project |
| API19_Edit Project | PUT /api/projects/{id} | ✅ | Update project |
| API20_Delete Project | DELETE /api/projects/{id} | ✅ | Soft delete project |
| API21_Assign PM For Project | POST /api/projects/{id}/assign-pm | ✅ | Assign PM |
| API22_Assign Members | POST /api/projects/{id}/assign-members | ✅ | Assign members |

**Files:**
- `ProjectController.php`
- `Project.php` (Model với SoftDeletes)
- `ProjectData.php`
- `ProjectDataCollection.php`
- `ProjectPolicy.php`
- `ProjectService.php`
- `ProjectRepository.php` + Interface
- `ProjectStatus.php` (Enum)

### 5. Settings (API23-API24)
| API | Endpoint | Trạng thái | Mô tả |
|-----|----------|------------|-------|
| API23_Setting | GET /api/settings | ✅ | Get system settings |
| API24_Update setting | PUT /api/settings | ✅ | Update settings |

**Files:**
- `SettingController.php`
- `SettingData.php` ✅ **MỚI TẠO**

### 6. Schedule (API25-API26)
| API | Endpoint | Trạng thái | Mô tả |
|-----|----------|------------|-------|
| API25_Get Schedule Information | GET /api/schedules | ✅ | Get work schedule |
| API26_Update Schedule | PUT /api/schedules | ✅ | Update schedule |

**Files:**
- `SettingController.php`
- `ScheduleData.php` ✅ **MỚI TẠO**

### 7. Issue Management (API27-API33)
| API | Endpoint | Trạng thái | Mô tả |
|-----|----------|------------|-------|
| API27_List Of Issues | GET /api/issues | ✅ | List all issues |
| API28_Filter Issues | GET /api/issues/filter | ✅ | Filter by project, status, priority, assignee |
| API29_Create New Issue | POST /api/issues | ✅ | Create new issue |
| API30_Edit Issue | PUT /api/issues/{id} | ✅ | Update issue |
| API31_Delete Issue | DELETE /api/issues/{id} | ✅ | Soft delete issue |
| API32_Comment Issue | POST /api/issues/{id}/comments | ✅ | Add comment |
| API33_Log | GET /api/logs | ✅ | Get activity logs |

**Files:**
- `IssueController.php`
- `Issue.php` (Model với SoftDeletes)
- `IssueData.php`
- `IssueDataCollection.php`
- `IssuePolicy.php`
- `IssueService.php`
- `IssueRepository.php` + Interface
- `TaskCommentData.php` ✅ **MỚI TẠO**
- `TaskLogData.php` ✅ **MỚI TẠO**
- `TaskCommentDataCollection.php` ✅ **MỚI TẠO**
- `TaskLogDataCollection.php` ✅ **MỚI TẠO**

---

## ✅ Kiến trúc chi tiết đã triển khai

### Repository Layer
```
Repositories/
├── Contracts/
│   ├── IssueRepositoryInterface.php
│   ├── ProjectRepositoryInterface.php
│   └── UserRepositoryInterface.php
└── Eloquent/
    ├── BaseRepository.php
    ├── IssueRepository.php
    ├── ProjectRepository.php
    └── UserRepository.php
```

### Service Layer
```
Services/
├── AuthService.php
├── IssueService.php
└── ProjectService.php
```

### Policy + Gate (Authorization)
```
Policies/
├── IssuePolicy.php (viewAny, view, create, update, delete, restore, forceDelete)
├── ProjectPolicy.php
└── UserPolicy.php
```

### Events + Listeners
```
Events/
└── Issue/
    ├── IssueAssigned.php
    ├── IssueCommented.php
    ├── IssueCreated.php
    ├── IssueDeleted.php
    ├── IssueStatusChanged.php
    └── IssueUpdated.php

Listeners/
└── Issue/
    ├── NotifyIssueAssignedListener.php
    ├── NotifyIssueCommentedListener.php
    ├── NotifyIssueCreatedListener.php
    ├── NotifyIssueDeletedListener.php
    ├── NotifyIssueStatusChangedListener.php
    └── NotifyIssueUpdatedListener.php

Jobs/
└── SendIssueNotificationJob.php
```

### Exception Handling
```
Exceptions/
├── ApiException.php
├── AuthenticationException.php
├── AuthorizationException.php
├── Handler.php
├── NotFoundException.php
└── ValidationException.php
```

### Data Classes (DTO + Transformer)
```
Data/
├── RoleData.php ✅
├── SettingData.php ✅
├── ScheduleData.php ✅
├── Auth/
│   ├── LoginData.php
│   ├── RegisterData.php
│   └── UserData.php
├── Collections/
│   ├── IssueDataCollection.php
│   ├── ProjectDataCollection.php
│   ├── RoleDataCollection.php ✅
│   ├── TaskCommentDataCollection.php ✅
│   ├── TaskLogDataCollection.php ✅
│   └── UserDataCollection.php
├── Issue/
│   ├── IssueData.php
│   ├── TaskCommentData.php ✅
│   └── TaskLogData.php ✅
└── Projects/
    └── ProjectData.php
```

---

## ✅ Data Classes Tổng cộng

| # | Data Class | API | Trạng thái |
|---|------------|-----|------------|
| 1 | LoginData | API01 | ✅ |
| 2 | RegisterData | - | ✅ |
| 3 | UserData | API05-11 | ✅ |
| 4 | **RoleData** | API12-15 | ✅ Mới tạo |
| 5 | ProjectData | API16-22 | ✅ |
| 6 | **SettingData** | API23-24 | ✅ Mới tạo |
| 7 | **ScheduleData** | API25-26 | ✅ Mới tạo |
| 8 | IssueData | API27-31 | ✅ |
| 9 | **TaskCommentData** | API32 | ✅ Mới tạo |
| 10 | **TaskLogData** | API33 | ✅ Mới tạo |

**Tổng: 10 Data classes**

---

## 🔐 Phân quyền (Role-Based Access Control)

| Role | Quyền hạn |
|------|-----------|
| Admin | Full system (CRUD all) |
| PMO | Toàn bộ dự án |
| PM | Dự án được phân bổ |
| Dev/Tester/Comtor | Chỉ xem dự án được join |

**Kiểm tra trong Policies:**
```php
// UserPolicy
public function viewAny(User $user)
public function view(User $user, User $model)
public function create(User $user)
public function update(User $user, User $model)
public function delete(User $user, User $model)
public function restore(User $user, User $model)
public function forceDelete(User $user, User $model)

// ProjectPolicy
public function viewAny(User $user)
public function view(User $user, Project $model)
public function create(User $user)
public function update(User $user, Project $model)
public function delete(User $user, Project $model)
```

---

## 📅 Kế hoạch tiếp theo (Chưa làm)

| STT | Chức năng | Ưu tiên | Mô tả |
|-----|-----------|---------|-------|
| 1 | Position Management | Cao | CRUD positions (Dev Backend, Dev Frontend, Tester, PM...) |
| 2 | User Positions | Cao | Gán nhiều vị trí cho user |
| 3 | Project Member Roles | Trung | Phân quyền theo từng project member |
| 4 | Task Relations | Trung | Quan hệ giữa các ticket (blocks, parent/child...) |
| 5 | Time Entries | Thấp | Ghi nhận thời gian |
| 6 | Versions/Milestones | Thấp | Sprint, Release versions |
| 7 | Wiki System | Thấp | Wiki documentation |
| 8 | Document Management | Thấp | File uploads |

---

## 📊 Tổng kết

| Thành phần | Tổng số | Hoàn thành | Tỷ lệ |
|------------|---------|------------|-------|
| APIs | 33 | 33 | 100% |
| Data Classes | 10 | 10 | 100% |
| Repositories | 6 | 6 | 100% |
| Services | 3 | 3 | 100% |
| Policies | 3 | 3 | 100% |
| Events | 6 | 6 | 100% |
| Listeners | 6 | 6 | 100% |
| Jobs | 1 | 1 | 100% |
| Exceptions | 6 | 6 | 100% |

**Trạng thái: ✅ HOÀN THÀNH (100%)**
