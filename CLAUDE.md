<laravel-boost-guidelines>
# Smart Room Booking (SIMRU Teknik) Guidelines

## Zero Comment Rule (STRICT)
- Under NO circumstances should any written code contain comments.
- Do NOT include inline comments (//, /* */, #, <!-- -->).
- Do NOT include DocBlocks or PHPDocs; enforce self-documenting code with clear types, expressive method names, and descriptive variable names.
- Keep all PHP files, Blade views, JavaScript, and CSS completely free of comments.

## Architecture: Clean Code, SoC, & SRP
- Presentation Layer: Livewire components and reusable Blade components (`resources/views/components/`). Livewire components only orchestrate UI state, bind input, validate form requests, and trigger service methods.
- Service Layer (`app/Services/`): Encapsulate all business logic away from controllers and Livewire components:
  - `BookingService`: Slot availability, overlap detection, booking creation, cancellation, status transitions.
  - `QrCodeService`: QR generation, QR verification, payload generation.
  - `RoomService`: Room management, status queries, capacity, and facility filtering.
- Data Layer: Eloquent Models with explicit types, strict casts, and scopes.

## Database Schema (simru_teknik)
- `users`: `id`, `name`, `email`, `password`, `role` (admin, staff, student), `phone`, `department`, timestamps.
- `rooms`: `id`, `name`, `code` (unique), `capacity`, `location`, `facilities` (json), `status` (available, maintenance), `qr_identifier` (unique), timestamps.
- `bookings`: `id`, `booking_code` (unique), `user_id`, `room_id`, `purpose`, `start_time`, `end_time`, `status` (pending, confirmed, checked_in, completed, cancelled, rejected), `check_in_at`, `check_out_at`, `cancellation_reason`, timestamps.
- `booking_logs`: `id`, `booking_id`, `user_id`, `action`, `payload` (json), timestamps.

## Frontend & Assets
- Local Tailwind CSS stored in `public/assets/css/` (no external runtime CDN).
- Reusable Blade components for buttons, badges, cards, modals, and navigation.

## Testing & Code Quality
- Run `vendor/bin/pint --format agent` to format PHP code.
- Run `vendor/bin/pest` to verify feature and unit tests.
</laravel-boost-guidelines>
