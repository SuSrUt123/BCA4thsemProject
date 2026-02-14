# Order Billing Panel - Documentation

## Overview
A simple, clean admin panel for viewing and printing detailed order billing information.

## Features

### 1. **Simple & Clean Design**
- Modern, professional layout
- Easy to read and navigate
- Print-optimized styling

### 2. **Complete Information Display**
- **Company Information**
  - Business Name: Quick Bites
  - Registration No: QB-2025-001
  - Tax ID: 123456789
  - Contact details (Email, Phone, Address)

- **Customer Information**
  - Full name and username
  - Email and phone
  - Delivery address

- **Restaurant Information** (if available)
  - Restaurant name
  - Restaurant contact details
  - Restaurant address

- **Order Details**
  - Item name
  - Quantity
  - Unit price
  - Total price
  - Subtotal, delivery charges, tax
  - Grand total

- **Order Status & Payment**
  - Current order status with color-coded badges
  - Payment method
  - Order date and time
  - Order ID

- **Payment Information**
  - Bank details
  - Account information

### 3. **Print Functionality**
- One-click print button
- Optimized print layout
- Hides unnecessary elements when printing
- Professional invoice format

### 4. **Access Points**

#### For Admin:
- Navigate to: `admin/all_orders.php`
- Click the blue **invoice icon** (📄) next to any order
- Opens the billing panel: `admin/order_billing.php?order_id=X`

#### For Customers:
- Navigate to: `your_orders.php`
- Click the blue **invoice icon** (📄) next to any order
- Opens the customer invoice: `order_invoice.php?order_id=X`

## File Structure

```
admin/
├── order_billing.php          # Admin billing panel (NEW)
├── all_orders.php             # Updated with billing button
└── ...

order_invoice.php              # Customer invoice page (existing)
your_orders.php                # Updated with invoice button (existing)
```

## Usage

### Admin Panel:
1. Login to admin panel
2. Go to "Orders" section
3. Click the blue invoice icon (📄) for any order
4. View complete billing details
5. Click "Print Invoice" to print
6. Click "Back to Orders" to return

### Customer Panel:
1. Login as customer
2. Go to "My Orders"
3. Click the blue invoice icon (📄) for any order
4. View order invoice
5. Click "Print Invoice" to print
6. Click "Back to Orders" to return

## Design Features

- **Responsive**: Works on all screen sizes
- **Color-coded status badges**:
  - Blue: Dispatch
  - Yellow: On The Way
  - Green: Delivered
  - Red: Cancelled
- **Grid layout**: Information organized in clean grids
- **Print-friendly**: Optimized for printing
- **Professional styling**: Modern gradient header

## Security

- Admin authentication required
- Order ID validation
- SQL injection prevention
- Session-based access control

## Customization

To customize company information, edit the following in `admin/order_billing.php`:
- Business Name
- Registration Number
- Tax ID
- Email
- Phone
- Address
- Bank Details

## Browser Compatibility

- Chrome ✓
- Firefox ✓
- Safari ✓
- Edge ✓
- Opera ✓

## Print Settings

For best results when printing:
- Use "Print" button in the panel
- Select "Save as PDF" or your printer
- Use portrait orientation
- Enable background graphics (for colors)

---

**Created**: 2025
**Version**: 1.0
**Status**: Production Ready
