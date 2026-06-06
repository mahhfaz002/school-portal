# Test Plan

## 1. Automated tests

```bash
php artisan test
```

Expected: **all green** (56 assertions across auth, dashboards, modules, settings, announcements).

What's covered:
- Auth: login, logout, registration, password reset/confirm/update, email verification, profile.
- `DashboardTest`: every role (proprietor, principal, admin, ict, accountant, teacher, student, exam_officer) loads `/dashboard` with HTTP 200.
- `DashboardTest`: announcements page loads; settings page is admin-gated; settings update persists.
- `ModuleSmokeTest`: students, subjects, attendance (+report), inventory, admissions, promotion, score entry, announcements, settings, staff, timetable, library, exams, transport, HR, alumni all return 200 for an admin; announcement create persists.

Add new tests under `tests/Feature/` as you build features; run `php artisan test --filter=Name` to focus.

## 2. Manual QA checklist (after seeding)

Run `php artisan migrate:fresh --seed` first. Log in with the seeded accounts (`password`).

### Branding / customisation
- [ ] Log in as **proprietor** → user menu → **School Settings**.
- [ ] Change school name, upload a logo, pick a primary colour → Save. Nav shows the new name/logo; the brand colour applies to buttons.
- [ ] Edit the grading scheme (e.g. A ≥ 75) → open a student report card and confirm grades follow the new bands.
- [ ] Change currency symbol → dashboards/receipts show the new symbol.

### Per-role smoke
- [ ] Each seeded role logs in and sees its own dashboard without errors.
- [ ] **Teacher** (assigned JSS1A) sees their class; "Mark attendance" and "Enter scores" links work.

### Students & academics
- [ ] Create a student, edit, view profile, generate ID card and report card PDF.
- [ ] Add subjects; enter class scores for a subject; verify they appear on the report card with correct grade/remark.
- [ ] Promote a class via Promotion.

### Finance & attendance
- [ ] Record a payment for a student; open the printable receipt.
- [ ] Take attendance for a class; open the attendance report.

### Admissions
- [ ] As a guest, open `/apply`, submit with passport + birth certificate uploads → success message.
- [ ] As admin/ICT, open **Admissions**, approve/reject an application → status updates, action appears in the ICT activity log.

### Communications & ops
- [ ] Post an announcement to "Teachers"; confirm a teacher sees it and a student does not.
- [ ] Open Timetable, Library, Exams, Transport, HR, Alumni pages — all load.

### Security
- [ ] A newly created teacher is forced to change password on first login.
- [ ] A teacher cannot open `/settings` (403) or `/staff` management actions.

## 3. Pre-deploy verification

```bash
php artisan migrate:fresh --seed   # must complete with no errors (this is the deploy DB path)
npm run build                      # must build assets
php artisan test                   # must be green
php artisan config:cache && php artisan route:cache && php artisan config:clear
```
