# Database Notes

This project models the Head First OOAD "Rick's Guitar Shop" idea with a clear split between
**descriptive specs** and **store inventory**:

- `instrument_specs` holds searchable, reusable attributes (builder, type, woods, model, style).
- `instruments` represents the actual sellable inventory (serial number, price, condition, stock).

That split allows multiple inventory items to share the same specification record while keeping
store-specific data (pricing, stock, publish state) isolated.

## Key Tables

- `users` - admin and customer accounts.
- `instrument_families` - high-level groupings (guitars, basses, etc.).
- `instrument_types` - types within a family (acoustic, electric, etc.).
- `builders` - brands/makers.
- `wood` - wood options for back/top specs.
- `instrument_specs` - searchable specifications and descriptive metadata.
- `instruments` - inventory items tied to a spec, with price and stock state.
- `media` - Spatie Media Library table for instrument gallery images.
- `wishlist_items` - customer wishlists tied to instruments.
- `inquiries` - customer inquiries with admin assignment/status.
- `reservations` - reservation requests with status tracking.
- `price_histories` - records price changes for instruments.
- `audit_logs` - generic audit trail for admin CRUD activity.

## ERD

![ERD](erd/erd.png)

DBDiagram source:

```text
https://dbdiagram.io/d/69b66bac78c6c4bc7ae68db6
```
