<?php
$tutorial_title = 'Security Practices';
$tutorial_slug  = 'security';
$quiz_slug      = 'security';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Security is not a feature you add at the end — it is a discipline woven into every line of code, every architecture decision, and every deployment. The cost of a security breach — financial penalties, reputational damage, user data exposure — dwarfs the cost of building secure software from the start. Every developer is responsible for security; it is not just the security team\'s job. This tier introduces the most critical security concepts and the OWASP Top 10 — the most common web application vulnerabilities.</p>',
        'concepts' => [
            'CIA triad: Confidentiality, Integrity, Availability',
            'Attack surface: every input, API endpoint, dependency, and configuration file',
            'OWASP Top 10 2021: A01–A10 vulnerability categories',
            'Principle of least privilege: grant only the minimum permissions needed',
            'Defence in depth: multiple layers of security controls',
            'Security by design vs. security as an afterthought',
            'Threat modelling: STRIDE (Spoofing, Tampering, Repudiation, Info disclosure, DoS, Elevation)',
        ],
        'code' => [
            'title'   => 'OWASP Top 10 — SQL injection prevention',
            'lang'    => 'python',
            'content' =>
"# VULNERABLE: SQL Injection (OWASP A03:2021)
# Never do this — user input directly in SQL string
def get_user_BAD(username: str):
    query = f\"SELECT * FROM users WHERE username = '{username}'\"
    # username = \"' OR '1'='1\" → returns ALL users!
    return db.execute(query).fetchone()

# SECURE: Parameterised query — database driver escapes the value
def get_user_GOOD(username: str):
    query = 'SELECT id, name, email FROM users WHERE username = %s'
    return db.execute(query, (username,)).fetchone()

# ALSO VULNERABLE: Command injection (OWASP A03)
import subprocess
def ping_BAD(host: str):
    return subprocess.run(f'ping -c 1 {host}', shell=True)
    # host = '127.0.0.1; rm -rf /' → executes arbitrary commands!

# SECURE: Shell=False with argument list
def ping_GOOD(host: str):
    # Validate input before using it
    import re
    if not re.match(r'^[a-zA-Z0-9._-]+$', host):
        raise ValueError('Invalid hostname')
    return subprocess.run(['ping', '-c', '1', host], shell=False,
                          capture_output=True, timeout=5)",
        ],
        'tips' => [
            'Use parameterised queries (prepared statements) for ALL database operations — never string interpolation.',
            'Validate and whitelist all user input — reject anything that doesn\'t match the expected pattern.',
            'Never use shell=True in subprocess with user-controlled input — use a list of arguments instead.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Authentication and authorisation are the twin pillars of access control. Authentication proves who you are (identity); authorisation determines what you can do (permissions). Secure password handling — hashing with bcrypt/Argon2, never storing plaintext, rate-limiting login attempts — prevents credential theft from being catastrophic. JWT (JSON Web Tokens) is the dominant stateless authentication mechanism for APIs, but has important security considerations when implemented incorrectly.</p>',
        'concepts' => [
            'Authentication vs. authorisation (AuthN vs. AuthZ)',
            'Password security: bcrypt/Argon2 hashing, salting, work factors',
            'Why MD5/SHA1 are wrong for passwords: speed, rainbow tables',
            'JWT: header.payload.signature; RS256 vs. HS256; short expiry + refresh tokens',
            'JWT pitfalls: alg:none attack, secret leakage, missing expiry, signature bypass',
            'OAuth 2.0 and OpenID Connect: authorization code flow, PKCE for SPAs',
            'Multi-factor authentication (MFA): TOTP (RFC 6238), WebAuthn/FIDO2',
        ],
        'code' => [
            'title'   => 'Secure password hashing with bcrypt',
            'lang'    => 'python',
            'content' =>
"import bcrypt
import secrets
from datetime import datetime, timedelta, timezone
import jwt  # PyJWT

# --- Password hashing ---
def hash_password(plain: str) -> str:
    # bcrypt auto-generates a salt; work_factor=12 ≈ 300ms on modern hardware
    return bcrypt.hashpw(plain.encode(), bcrypt.gensalt(rounds=12)).decode()

def verify_password(plain: str, hashed: str) -> bool:
    return bcrypt.checkpw(plain.encode(), hashed.encode())

# --- JWT creation and verification (RS256) ---
def create_access_token(user_id: int, private_key: str) -> str:
    return jwt.encode(
        {
            'sub': str(user_id),
            'iat': datetime.now(timezone.utc),
            'exp': datetime.now(timezone.utc) + timedelta(minutes=15),
            'jti': secrets.token_hex(16),  # unique token ID for revocation
        },
        private_key,
        algorithm='RS256',
    )

def verify_access_token(token: str, public_key: str) -> dict:
    return jwt.decode(
        token,
        public_key,
        algorithms=['RS256'],  # NEVER pass algorithms=['none'] or include HS256 when using RS256
        options={'require': ['exp', 'sub', 'iat', 'jti']},
    )",
        ],
        'tips' => [
            'Always use bcrypt, Argon2id, or scrypt for passwords — never MD5, SHA1, or SHA256 without salt/stretching.',
            'Use RS256 (asymmetric) JWT in production — HS256 requires sharing the secret with every service that verifies tokens.',
            'Never log JWT tokens — they are credentials. Log the jti (JWT ID) for audit trails instead.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Web security headers, HTTPS enforcement, and Content Security Policy (CSP) protect against browser-based attacks. Cross-Site Scripting (XSS) — injecting malicious scripts into web pages — is the most prevalent web vulnerability; output encoding and a strict CSP are the defences. Cross-Site Request Forgery (CSRF) tricks authenticated users into unknowingly submitting requests; CSRF tokens and SameSite cookies are the mitigations.</p><p>Secrets management — environment variables, vault systems, and secret scanning in CI — prevents credentials from being committed to source code or exposed in logs.</p>',
        'concepts' => [
            'XSS (A03): stored, reflected, and DOM-based; output encoding, DOMPurify, CSP',
            'CSRF: SameSite=Strict/Lax cookies, synchroniser token pattern',
            'HTTP security headers: HSTS, CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy',
            'Secrets management: environment variables, HashiCorp Vault, AWS Secrets Manager',
            'Secret scanning: GitHub secret scanning, truffleHog, gitleaks',
            'Sensitive data exposure (A02): encryption at rest (AES-256-GCM), in transit (TLS 1.3)',
            'Dependency vulnerabilities (A06): npm audit, pip-audit, Dependabot, Snyk',
        ],
        'code' => [
            'title'   => 'Security headers middleware',
            'lang'    => 'python',
            'content' =>
"from fastapi import FastAPI
from fastapi.middleware.trustedhost import TrustedHostMiddleware
from starlette.middleware.base      import BaseHTTPMiddleware
from starlette.requests             import Request

class SecurityHeadersMiddleware(BaseHTTPMiddleware):
    async def dispatch(self, request: Request, call_next):
        response = await call_next(request)

        # Prevent MIME-type sniffing
        response.headers['X-Content-Type-Options']    = 'nosniff'
        # Prevent clickjacking
        response.headers['X-Frame-Options']           = 'DENY'
        # Strict Transport Security (HTTPS only, 1 year)
        response.headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains; preload'
        # Referrer information control
        response.headers['Referrer-Policy']           = 'strict-origin-when-cross-origin'
        # Permissions Policy: deny camera/mic access
        response.headers['Permissions-Policy']        = 'camera=(), microphone=(), geolocation=()'
        # Content Security Policy
        response.headers['Content-Security-Policy']   = (
            \"default-src 'self'; \"
            \"script-src 'self' 'nonce-{nonce}'; \"
            \"style-src 'self' 'unsafe-inline'; \"
            \"img-src 'self' data: https:; \"
            \"connect-src 'self'; \"
            \"frame-ancestors 'none'\"
        )
        return response",
        ],
        'tips' => [
            'Run npm audit or pip-audit in CI and fail the build on high/critical vulnerabilities.',
            'Use gitleaks or truffleHog in pre-commit hooks to prevent secrets from ever being committed.',
            'Test your security headers at securityheaders.com before going to production.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced security engineering covers threat modelling for system design reviews, penetration testing methodologies (OWASP Testing Guide), and secure software development lifecycle (S-SDLC) practices. Cryptography — symmetric (AES-GCM), asymmetric (RSA, EC), and hashing (SHA-2, SHA-3) — requires understanding both the algorithms and the secure use of the standard library implementations (never roll your own crypto).</p><p>Zero-trust architecture, mTLS for service-to-service communication, and runtime application self-protection (RASP) represent the advanced production security posture.</p>',
        'concepts' => [
            'Threat modelling: STRIDE, PASTA, data flow diagrams, attack trees',
            'Cryptography: AES-256-GCM, RSA-OAEP, ECDH key exchange, PBKDF2/Argon2',
            'Envelope encryption: data encryption key (DEK) wrapped with key encryption key (KEK)',
            'Zero-trust: never trust, always verify; micro-segmentation; BeyondCorp model',
            'mTLS: mutual TLS for service mesh; certificate management with cert-manager',
            'SAST (static analysis): Semgrep, Bandit, CodeQL, SonarQube',
            'DAST (dynamic analysis): OWASP ZAP, Burp Suite, fuzzing',
        ],
        'code' => [
            'title'   => 'AES-256-GCM authenticated encryption',
            'lang'    => 'python',
            'content' =>
"from cryptography.hazmat.primitives.ciphers.aead import AESGCM
import os

def encrypt(plaintext: bytes, key: bytes, associated_data: bytes = b'') -> bytes:
    '''AES-256-GCM authenticated encryption.
    Returns: nonce (12 bytes) + ciphertext + authentication tag (16 bytes).
    associated_data is authenticated but NOT encrypted (e.g., record ID).
    '''
    aesgcm  = AESGCM(key)
    nonce   = os.urandom(12)          # 96-bit random nonce — NEVER reuse
    ct      = aesgcm.encrypt(nonce, plaintext, associated_data)
    return nonce + ct                 # prepend nonce for decryption

def decrypt(token: bytes, key: bytes, associated_data: bytes = b'') -> bytes:
    '''Decrypts and verifies AES-256-GCM ciphertext.
    Raises: cryptography.exceptions.InvalidTag if tampered.
    '''
    aesgcm = AESGCM(key)
    nonce, ct = token[:12], token[12:]
    return aesgcm.decrypt(nonce, ct, associated_data)

# Key generation — store in Vault, not code
key     = os.urandom(32)  # 256-bit key
secret  = b'sensitive user data'
token   = encrypt(secret, key, associated_data=b'user:42')
message = decrypt(token, key, associated_data=b'user:42')",
        ],
        'tips' => [
            'Never reuse a nonce with the same key in AES-GCM — one reuse enables full message recovery.',
            'Use the cryptography library (pyca/cryptography), not pycrypto/Crypto — the latter is unmaintained.',
            'Authenticated encryption (AES-GCM) provides confidentiality AND integrity — always prefer it over AES-CBC.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert security engineering involves designing and operating security programmes — vulnerability disclosure policies, bug bounty programmes, security incident response plans, and compliance frameworks (SOC 2, ISO 27001, PCI DSS, HIPAA). Red teaming (attacker simulation), blue teaming (detection and response), and purple teaming (collaborative improvement) represent the full spectrum of operational security.</p><p>Contributing to security research — CVE assignment, responsible disclosure, conference presentations at DEF CON or Black Hat — and deep knowledge of exploit development (memory corruption, use-after-free, format string vulnerabilities) mark the frontier of expert security practice.</p>',
        'concepts' => [
            'Security programmes: PSIRT, bug bounty (HackerOne, Bugcrowd), VDP',
            'Incident response: NIST IR lifecycle, forensics, chain of custody, post-mortem',
            'Compliance: SOC 2 Type II, ISO 27001, PCI DSS, HIPAA/HITECH, GDPR security',
            'Red team operations: kill chain, living off the land, lateral movement, C2 frameworks',
            'Blue team: SIEM (Splunk, Elastic SIEM), EDR, SOAR, threat intelligence feeds',
            'Memory safety vulnerabilities: buffer overflow, use-after-free, type confusion',
            'Responsible disclosure: CVE process, CVSS scoring, embargo coordination',
        ],
        'code' => [
            'title'   => 'Security event logging for SIEM',
            'lang'    => 'python',
            'content' =>
"import logging
import json
import time
from typing import Any

# Structured security event logger for SIEM ingestion
class SecurityAuditLogger:
    def __init__(self, service_name: str):
        self.logger = logging.getLogger('security.audit')
        self.service = service_name

    def _event(self, category: str, action: str, outcome: str,
                user_id: str | None, ip: str | None, **extra: Any):
        event = {
            '@timestamp':    time.strftime('%Y-%m-%dT%H:%M:%S.000Z', time.gmtime()),
            'service':       self.service,
            'category':      category,
            'action':        action,
            'outcome':       outcome,
            'user.id':       user_id,
            'source.ip':     ip,
            **extra,
        }
        # Use ECS (Elastic Common Schema) field naming for SIEM compatibility
        self.logger.info(json.dumps(event))

    def login_success(self, user_id: str, ip: str):
        self._event('authentication', 'login', 'success', user_id, ip)

    def login_failure(self, username: str, ip: str, reason: str):
        self._event('authentication', 'login', 'failure', None, ip,
                    **{'user.name': username, 'event.reason': reason})

    def access_denied(self, user_id: str, resource: str, action: str, ip: str):
        self._event('authorization', action, 'denied', user_id, ip,
                    **{'resource.name': resource})",
        ],
        'tips' => [
            'Use ECS (Elastic Common Schema) field names in security logs — SIEM tools parse them automatically.',
            'Never log passwords, tokens, or PII in security logs — log IDs, actions, and outcomes only.',
            'Read "The Web Application Hacker\'s Handbook" and "The Art of Exploitation" for deep vulnerability understanding.',
            'Follow CISA advisories, CVE NVD, and Krebs on Security for current threat intelligence.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
