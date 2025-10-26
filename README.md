# 🏆 Advanced CTF Challenge Platform

A comprehensive collection of 9 high-level cybersecurity challenges covering various vulnerability types from basic to insane difficulty levels.

## 🚀 Quick Start

```bash
# Start all challenges
docker compose up -d

# Stop all challenges
docker compose down
```

## 📋 Challenge Overview

| Lab | Port | Vulnerability | Difficulty | Flag Location |
|-----|------|---------------|------------|---------------|
| Lab 1 | 5001 | Local File Inclusion (LFI) | Medium | `file://flag.txt` |
| Lab 2 | 5002 | Command Injection | Medium | Filename execution |
| Lab 3 | 5003 | IDOR | Medium | File ID 55 |
| Lab 4 | 5004 | SQL Injection | Hard | Authentication bypass |
| Lab 5 | 5005 | Advanced XSS | Insane | Admin panel XSS |
| Lab 6 | 5006 | SSRF | Insane | Internal file access |
| Lab 7 | 5007 | Deserialization | Insane | Object injection |
| Lab 8 | 5008 | Race Condition | Insane | Balance manipulation |
| Lab 9 | 5009 | Advanced SQLi | Insane | Multi-layer bypass |

---

## 🔍 Detailed Solutions

### Lab 1: Local File Inclusion (LFI) - Port 5001
**Difficulty:** Medium  
**Vulnerability:** Local File Inclusion with `file://` wrapper

**How to Solve:**
1. Access http://localhost:5001
2. Click on any blog post image
3. Modify the URL to: `?image=file://flag.txt`
4. The flag will be displayed as raw text

**Key Points:**
- Only `file://` wrapper is allowed
- Direct `flag.txt` access is blocked
- Raw file content is displayed without HTML wrapper

---

### Lab 2: Command Injection - Port 5002
**Difficulty:** Medium  
**Vulnerability:** Command injection via filename

**How to Solve:**
1. Access http://localhost:5002
2. Upload a file with command in filename (e.g., `cat flag.txt`)
3. The command will execute and display the flag

**Key Points:**
- Commands are executed from filename
- Only shows output if command keywords are detected
- Multiple PHP execution functions are used

---

### Lab 3: IDOR - Port 5003
**Difficulty:** Medium  
**Vulnerability:** Insecure Direct Object Reference

**How to Solve:**
1. Access http://localhost:5003
2. Upload any file to get a download link
3. Capture the download request (e.g., `/download/1`)
4. Brute force file IDs: `/download/55`
5. The flag will be returned for ID 55

**Key Points:**
- File ID 55 contains the flag
- File 55 is hidden from the frontend listing
- Direct API access reveals the flag

---

### Lab 4: SQL Injection - Port 5004
**Difficulty:** Hard  
**Vulnerability:** SQL injection authentication bypass

**How to Solve:**
1. Access http://localhost:5004
2. Use SQL injection payloads:
   - Username: `' OR '1'='1`
   - Password: `' OR '1'='1`
3. Alternative: `' OR 1=1 --`
4. Login successfully to access dashboard with flag

**Key Points:**
- Credentials are extremely complex (30+ characters)
- SQL injection is the only viable method
- No hints about usernames/passwords

---

### Lab 5: Advanced XSS - Port 5005
**Difficulty:** Insane  
**Vulnerability:** Cross-Site Scripting with multiple filter bypasses

**How to Solve:**
1. Access http://localhost:5005
2. Submit a comment with XSS payload that bypasses filters:
   ```html
   <img src=x onerror="fetch('/admin.php?admin=true').then(r=>r.text()).then(d=>document.body.innerHTML=d)">
   ```
3. Alternative payloads:
   ```html
   <svg onload="location='admin.php?admin=true'">
   <iframe src="admin.php?admin=true"></iframe>
   ```
4. Access admin panel to get the flag

**Key Points:**
- Multiple layers of filtering (HTML entities, keyword blocking, regex)
- Admin panel accessible via `?admin=true`
- Flag is displayed in admin panel

---

### Lab 6: SSRF - Port 5006
**Difficulty:** Insane  
**Vulnerability:** Server-Side Request Forgery with multiple bypasses

**How to Solve:**
1. Access http://localhost:5006
2. Use SSRF payloads to bypass filters:
   - `http://0.0.0.0/flag.txt`
   - `http://127.0.0.1:80/flag.txt`
   - `http://[::1]/flag.txt`
   - `http://localhost./flag.txt`
3. Alternative bypasses:
   - `http://0x7f000001/flag.txt` (hex encoding)
   - `http://2130706433/flag.txt` (decimal encoding)

**Key Points:**
- Multiple IP blocking mechanisms
- Port restrictions
- Protocol filtering
- Various encoding bypasses work

---

### Lab 7: Deserialization - Port 5007
**Difficulty:** Insane  
**Vulnerability:** PHP Object Injection

**How to Solve:**
1. Access http://localhost:5007
2. Create malicious serialized object:
   ```php
   O:4:"User":3:{s:8:"username";s:5:"admin";s:4:"role";s:5:"admin";s:7:"isAdmin";b:1;}
   ```
3. Alternative using Config class:
   ```php
   O:6:"Config":1:{s:8:"settings";a:1:{s:5:"admin";b:1;}}
   ```
4. Submit the serialized data
5. Flag will be displayed when object is destroyed/unserialized

**Key Points:**
- `__destruct()` and `__wakeup()` magic methods are vulnerable
- Two vulnerable classes: User and Config
- Flag is displayed when admin conditions are met

---

### Lab 8: Race Condition - Port 5008
**Difficulty:** Insane  
**Vulnerability:** Time-of-check to time-of-use (TOCTOU)

**How to Solve:**
1. Access http://localhost:5008
2. Use race condition to withdraw money simultaneously:
   - Start with $1000 balance
   - Need to reach exactly $0
3. Send multiple concurrent requests:
   - Request 1: Withdraw $500
   - Request 2: Withdraw $500
   - Both requests read $1000, both succeed
4. Alternative: Use tools like Burp Suite Intruder with multiple threads
5. When balance reaches exactly $0, flag is displayed

**Key Points:**
- 0.1 second processing delay creates race window
- Multiple concurrent requests can bypass balance checks
- Flag appears when balance equals exactly $0

---

### Lab 9: Advanced SQLi - Port 5009
**Difficulty:** Insane  
**Vulnerability:** Multi-layer SQL injection bypass

**How to Solve:**
1. Access http://localhost:5009
2. Bypass multiple filters using advanced techniques:
   - **Case variation:** `UnIoN SeLeCt`
   - **Comment injection:** `/**/UNION/**/SELECT`
   - **Encoding:** `%55%4e%49%4f%4e` (URL encoded UNION)
   - **Double encoding:** `%2555%254e%2549%254f%254e`
3. Search payloads:
   ```
   admin'/**/UNION/**/SELECT/**/1,2,3,4-- -
   admin'/**/UNION/**/SELECT/**/1,2,3,4#/*
   admin'/**/UNION/**/SELECT/**/1,2,3,4%23
   ```
4. Alternative: Search for `admin` or `flag` to trigger flag display

**Key Points:**
- Multiple keyword blocking mechanisms
- Special character filtering
- Case-sensitive and case-insensitive filters
- Various encoding and comment techniques work

---

## 🛠️ Technical Details

### Database Setup
- **MySQL 8.0** for Labs 4 and 9
- **Root password:** `password`
- **Databases:** `sqli_lab`, `advanced_sqli_lab`

### File Structure
```
├── lab-1-php-blog/          # LFI Challenge
├── lab-2-file-upload/       # Command Injection
├── lab-3-idor/             # IDOR Challenge
├── lab-4-sqli/             # SQL Injection
├── lab-5-xss/              # Advanced XSS
├── lab-6-ssrf/             # SSRF Challenge
├── lab-7-deserialization/  # Deserialization
├── lab-8-race-condition/   # Race Condition
├── lab-9-advanced-sqli/    # Advanced SQLi
└── docker-compose.yml
```

### Security Features
- No hints in application interfaces
- Complex credentials where applicable
- Multiple layers of filtering
- Realistic vulnerability scenarios

---

## 🎯 Learning Objectives

Each challenge teaches specific security concepts:

1. **LFI:** File inclusion vulnerabilities and protocol wrappers
2. **Command Injection:** System command execution via user input
3. **IDOR:** Direct object reference vulnerabilities
4. **SQL Injection:** Database query manipulation
5. **XSS:** Cross-site scripting and filter bypasses
6. **SSRF:** Server-side request forgery and bypass techniques
7. **Deserialization:** Object injection and magic methods
8. **Race Conditions:** Time-of-check vulnerabilities
9. **Advanced SQLi:** Multi-layer injection bypasses

---

## ⚠️ Disclaimer

These challenges are for educational purposes only. Use the techniques learned responsibly and only on systems you own or have explicit permission to test.

---

## 🏆 Flags Summary

- **Lab 1:** `CTF{path_traversal_master}`
- **Lab 2:** `CTF{command_injection_master}`
- **Lab 3:** `CTF{idor_master_flag}`
- **Lab 4:** `CTF{sql_injection_master}`
- **Lab 5:** `CTF{xss_master_insane}`
- **Lab 6:** `CTF{ssrf_master_insane}`
- **Lab 7:** `CTF{deserialization_master_insane}`
- **Lab 8:** `CTF{race_condition_master_insane}`
- **Lab 9:** `CTF{advanced_sqli_master_insane}`

Good luck and happy hacking! 🚀
