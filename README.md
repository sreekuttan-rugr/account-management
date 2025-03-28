# Laravel Account Management System

## 📌 Overview
This is a **Laravel-based Account Management System** with the following features:
✅ **User Authentication** (Laravel Sanctum)  
✅ **Account Creation & Management** (Luhn Algorithm for Account Numbers)  
✅ **Transactions (Credit/Debit)**  
✅ **Fund Transfers Between Accounts**  
✅ **PDF Account Statements**  
✅ **Security Features (Rate Limiting, Input Validation, Authorization)**  

---

## 🛠️ Installation & Setup

### **1️⃣ Clone the Repository**
```bash
https://github.com/sreekuttan-rugr/account-management.git
cd account-management
```

### **2️⃣ Install Dependencies**
```bash
composer install
```

### **3️⃣ Configure Environment**
Copy `.env.example` to `.env` and update database settings:
```bash
cp .env.example .env
```
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=account_management
DB_USERNAME=root
DB_PASSWORD=
```

### **4️⃣ Generate Application Key**
```bash
php artisan key:generate
```

### **5️⃣ Run Migrations**
```bash
php artisan migrate
```

### **6️⃣ Install Laravel Sanctum & Seed Database**
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate --seed
```

### **7️⃣ Start the Server**
```bash
php artisan serve
```

---

## 🔑 API Authentication
This project uses **Laravel Sanctum** for authentication.

### **Register a User**
```http
POST /api/register
```
**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```
**Response:**
```json
{
  "message": "User registered successfully"
}
```

### **Login & Get Token**
```http
POST /api/login
```
**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```
**Response:**
```json
{
  "access_token": "your_generated_token",
  "token_type": "Bearer"
}
```

### **Logout**
```http
POST /api/logout
```
**Response:**
```json
{
  "message": "Logged out successfully"
}
```

---

## 📂 API Endpoints

### **Accounts**
| Method | Endpoint | Description |
|--------|----------------------|------------------|
| **POST** | `/api/accounts` | Create an account |
| **GET** | `/api/accounts/{account_number}` | Get account details |
| **PUT** | `/api/accounts/{account_number}` | Update account (except number) |
| **DELETE** | `/api/accounts/{account_number}` | Soft delete (deactivate) account |
| **PATCH** | `/api/accounts/{account_number}/restore` | Restore a soft-deleted account |

#### **Create Account Example**
**Request:**
```json
{
    "account_name": "John's Business",
    "account_type": "Business",
    "currency": "USD",
    "initial_balance": 500
}
```
**Response:**
```json
{
    "message": "Account created successfully",
    "account": {
        "account_name": "John's Business",
        "account_number": "492174385610",
        "account_type": "Business",
        "currency": "USD",
        "balance": 500
    }
}
```

### **Transactions**
| Method | Endpoint | Description |
|--------|------------------------|------------------|
| **POST** | `/api/transactions` | Log a credit or debit transaction |
| **GET** | `/api/transactions?account_number=X` | Get transactions for an account |

#### **Transaction Example**
**Request:**
```json
{
    "account_number": "492174385610",
    "type": "Credit",
    "amount": 100.50,
    "description": "Salary Deposit"
}
```
**Response:**
```json
{
    "message": "Transaction recorded successfully"
}
```

### **Fund Transfers**
| Method | Endpoint | Description |
|--------|------------------------------|------------------|
| **POST** | `/api/transactions/transfer` | Transfer funds between accounts |

#### **Transfer Example**
**Request:**
```json
{
    "from_account_number": "123456789012",
    "to_account_number": "987654321098",
    "amount": 50
}
```
**Response:**
```json
{
    "message": "Transfer successful"
}
```

### **PDF Account Statement**
| Method | Endpoint | Description |
|--------|--------------------------------|------------------|
| **GET** | `/api/accounts/{account_number}/statement` | Generate & download PDF account statement |

---

## 🔒 Security Features
✅ **Authentication:** Uses Laravel Sanctum for secure API access.  
✅ **Authorization:** Users can only access their own accounts.  
✅ **Rate Limiting:** 60 requests per minute (configurable in `Kernel.php`).  
✅ **Validation:** Prevents invalid inputs and unauthorized transactions.  
✅ **UUIDs for Security:** Prevents ID enumeration attacks.

---

## 📌 Future Improvements
- Implement **Laravel Policies** for role-based access control.
- Add **Admin Dashboard** for account management.
- Introduce **Multi-Currency Support** for transactions.
- Implement **Email Notifications** for transactions.

---

