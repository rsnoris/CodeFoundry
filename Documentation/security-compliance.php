<?php
$page_title  = 'Security & Compliance - CodeFoundry Documentation';
$active_page = '';
$page_styles = <<<'PAGECSS'
:root {
  --navy: #0e1828;
  --navy-2: #121c2b;
  --navy-3: #161f2f;
  --primary: #18b3ff;
  --primary-hover: #009de0;
  --text: #fff;
  --text-muted: #92a3bb;
  --text-subtle: #627193;
  --border-color: #1a2942;
  --button-outline: #ffffff22;
  --button-radius: 8px;
  --maxwidth: 1200px;
  --card-radius: 12px;
  --header-height: 68px;
  --mobile-menu-bg: #0e1828f9;
  --code-bg: #0b1220;
  --green: #7ad9a8;
  --amber: #f4b860;
}
html, body {
  background: var(--navy-2);
  color: var(--text);
  font-family: 'Inter', sans-serif;
  margin: 0;
  padding: 0;
}
body { min-height: 100vh; }
a { color: inherit; text-decoration: none; }

main {
  max-width: var(--maxwidth);
  margin: 0 auto;
  padding: 60px 40px;
}
@media (max-width: 768px) { main { padding: 40px 20px; } }

.breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: var(--text-muted);
  margin-bottom: 40px;
  flex-wrap: wrap;
}
.breadcrumb a { color: var(--primary); font-weight: 600; }
.breadcrumb a:hover { color: var(--primary-hover); }
.breadcrumb-sep { color: var(--text-subtle); }

.back-link {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: var(--primary);
  font-weight: 700;
  font-size: 14px;
  margin-bottom: 32px;
}
.back-link:hover { color: var(--primary-hover); }

.page-header { margin-bottom: 60px; }
.page-badge {
  display: inline-block;
  background: rgba(24, 179, 255, 0.15);
  color: var(--primary);
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  margin-bottom: 16px;
}
.page-title {
  font-size: 2.8rem;
  font-weight: 900;
  margin: 0 0 16px 0;
  letter-spacing: -1.5px;
  line-height: 1.1;
}
.page-desc {
  font-size: 1.15rem;
  color: var(--text-muted);
  max-width: 680px;
  line-height: 1.6;
}
@media (max-width: 768px) {
  .page-title { font-size: 2rem; }
  .page-desc  { font-size: 1rem; }
}

.section { margin-bottom: 64px; }
.section-title {
  font-size: 1.6rem;
  font-weight: 800;
  margin: 0 0 8px 0;
  letter-spacing: -0.5px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.section-title iconify-icon { color: var(--primary); font-size: 1.4rem; }
.section-subtitle {
  color: var(--text-muted);
  font-size: 1rem;
  margin: 0 0 28px 0;
  line-height: 1.6;
}
.section-divider {
  border: none;
  border-top: 1px solid var(--border-color);
  margin: 0 0 28px 0;
}

/* Security pillars */
.pillars-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 20px;
}
@media (max-width: 768px) { .pillars-grid { grid-template-columns: 1fr; } }

.pillar-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 28px;
  transition: border-color 0.2s, transform 0.2s;
}
.pillar-card:hover {
  border-color: var(--primary);
  transform: translateY(-3px);
}
.pillar-icon {
  font-size: 2.2rem;
  color: var(--primary);
  margin-bottom: 14px;
  display: block;
}
.pillar-card h3 {
  font-size: 1.1rem;
  font-weight: 800;
  margin: 0 0 10px 0;
}
.pillar-card p {
  color: var(--text-muted);
  font-size: 0.88rem;
  line-height: 1.6;
  margin: 0 0 14px 0;
}
.pillar-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.pillar-list li {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  color: var(--text-muted);
  font-size: 0.85rem;
  line-height: 1.5;
}
.pillar-list li::before {
  content: '▸';
  color: var(--primary);
  flex-shrink: 0;
}

/* Compliance frameworks */
.compliance-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 20px;
}
@media (max-width: 768px) { .compliance-grid { grid-template-columns: 1fr; } }

.compliance-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 28px;
  transition: border-color 0.2s;
}
.compliance-card:hover { border-color: var(--primary); }
.compliance-badge {
  display: inline-block;
  background: rgba(122,217,168,0.15);
  color: var(--green);
  border-radius: 8px;
  padding: 4px 12px;
  font-size: 12px;
  font-weight: 800;
  letter-spacing: 0.3px;
  margin-bottom: 14px;
}
.compliance-card h3 {
  font-size: 1.05rem;
  font-weight: 800;
  margin: 0 0 8px 0;
}
.compliance-card p {
  color: var(--text-muted);
  font-size: 0.88rem;
  line-height: 1.6;
  margin: 0 0 14px 0;
}
.compliance-controls {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}
.control-tag {
  background: rgba(24,179,255,0.1);
  color: var(--primary);
  border-radius: 5px;
  padding: 2px 9px;
  font-size: 11px;
  font-weight: 700;
}

/* Secure coding */
.practices-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}
@media (max-width: 768px) { .practices-grid { grid-template-columns: 1fr; } }

.practice-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 26px;
  transition: border-color 0.2s;
}
.practice-card:hover { border-color: var(--primary); }
.practice-card h3 {
  font-size: 1rem;
  font-weight: 800;
  margin: 0 0 10px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}
.practice-card h3 iconify-icon { color: var(--primary); font-size: 1.2rem; }
.practice-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.practice-list li {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  color: var(--text-muted);
  font-size: 0.88rem;
  line-height: 1.5;
}
.practice-list li::before {
  content: '▸';
  color: var(--primary);
  flex-shrink: 0;
}

/* Vulnerability table */
.vuln-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.9rem;
}
.vuln-table th {
  background: var(--navy-3);
  color: var(--text-muted);
  font-weight: 700;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 12px 16px;
  text-align: left;
  border-bottom: 1px solid var(--border-color);
}
.vuln-table td {
  padding: 14px 16px;
  border-bottom: 1px solid var(--border-color);
  vertical-align: top;
  line-height: 1.5;
}
.vuln-table tr:last-child td { border-bottom: none; }
.vuln-table tr:hover td { background: rgba(24,179,255,0.04); }
.table-wrapper {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  overflow: hidden;
  overflow-x: auto;
}
.severity-badge {
  display: inline-block;
  padding: 2px 10px;
  border-radius: 5px;
  font-size: 11px;
  font-weight: 800;
  min-width: 70px;
  text-align: center;
}
.sev-critical { background: rgba(255,70,70,0.2);   color: #ff4646; }
.sev-high     { background: rgba(255,158,110,0.2); color: #ff9e6e; }
.sev-medium   { background: rgba(244,184,96,0.2);  color: #f4b860; }
.sev-low      { background: rgba(122,217,168,0.2); color: #7ad9a8; }

/* Incident response timeline */
.ir-timeline {
  display: flex;
  flex-direction: column;
  gap: 0;
  position: relative;
  padding-left: 32px;
}
.ir-timeline::before {
  content: '';
  position: absolute;
  left: 10px;
  top: 10px;
  bottom: 10px;
  width: 2px;
  background: var(--border-color);
}
.ir-step {
  display: flex;
  gap: 20px;
  padding: 0 0 28px 0;
  position: relative;
}
.ir-step::before {
  content: '';
  position: absolute;
  left: -26px;
  top: 6px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: var(--primary);
  border: 2px solid var(--navy-2);
}
.ir-step-body {}
.ir-step-body h3 {
  font-size: 1rem;
  font-weight: 800;
  margin: 0 0 6px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}
.ir-step-body h3 .ir-time {
  background: rgba(24,179,255,0.12);
  color: var(--primary);
  border-radius: 5px;
  padding: 2px 8px;
  font-size: 11px;
  font-weight: 700;
}
.ir-step-body p {
  color: var(--text-muted);
  font-size: 0.88rem;
  line-height: 1.6;
  margin: 0;
}

/* Next steps */
.next-steps-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 16px;
}
.next-step-link {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 22px 24px;
  display: flex;
  align-items: center;
  gap: 14px;
  font-weight: 700;
  font-size: 0.95rem;
  transition: border-color 0.2s, background 0.2s;
}
.next-step-link:hover {
  border-color: var(--primary);
  background: rgba(24,179,255,0.07);
  color: var(--primary);
}
.next-step-link iconify-icon { font-size: 1.4rem; color: var(--primary); flex-shrink: 0; }

.warn-box {
  background: rgba(244,184,96,0.08);
  border: 1px solid rgba(244,184,96,0.3);
  border-radius: 10px;
  padding: 16px 20px;
  font-size: 0.9rem;
  color: var(--text-muted);
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 24px;
}
.warn-box iconify-icon { color: var(--amber); font-size: 1.2rem; flex-shrink: 0; margin-top: 1px; }
PAGECSS;
$page_scripts = <<<'PAGEJS'
const menuBtn = document.getElementById('mobileMenuBtn');
const mobileNav = document.getElementById('mobileNav');
const closeBtn = document.getElementById('closeMobileNav');
function closeMobileNav() { mobileNav.classList.remove('open'); }
if (menuBtn) menuBtn.onclick = () => mobileNav.classList.add('open');
if (closeBtn) closeBtn.onclick = closeMobileNav;
if (mobileNav) mobileNav.onclick = (e) => { if (e.target === mobileNav) closeMobileNav(); };
PAGEJS;
require_once __DIR__ . '/../includes/header.php';
?>

<main>
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="/Documentation/">Documentation Hub</a>
    <span class="breadcrumb-sep"><iconify-icon icon="lucide:chevron-right"></iconify-icon></span>
    <span>Security &amp; Compliance</span>
  </nav>

  <a href="/Documentation/" class="back-link">
    <iconify-icon icon="lucide:arrow-left"></iconify-icon>
    Back to Documentation
  </a>

  <div class="page-header">
    <span class="page-badge">Security Reference</span>
    <h1 class="page-title">Security &amp; Compliance</h1>
    <p class="page-desc">
      Comprehensive security guidelines, compliance framework mappings, secure coding practices, and vulnerability management procedures for CodeFoundry-hosted workloads.
    </p>
  </div>

  <!-- Security Guidelines -->
  <section class="section" id="security-guidelines">
    <h2 class="section-title">
      <iconify-icon icon="lucide:shield-check"></iconify-icon>
      Security Guidelines
    </h2>
    <p class="section-subtitle">These guidelines cover the four foundational pillars of application security: authentication, authorisation, data protection, and network security.</p>
    <hr class="section-divider">
    <div class="pillars-grid">
      <div class="pillar-card">
        <iconify-icon icon="lucide:user-check" class="pillar-icon"></iconify-icon>
        <h3>Authentication</h3>
        <p>Verifying that users and systems are who they claim to be is the first line of defence. Apply these controls to all authentication flows.</p>
        <ul class="pillar-list">
          <li>Enforce MFA for all human accounts, especially privileged roles.</li>
          <li>Use short-lived JWT access tokens (15–60 min) with rolling refresh tokens.</li>
          <li>Implement account lockout after 5 failed attempts; alert on anomalous login patterns.</li>
          <li>Store passwords using bcrypt (cost ≥ 12) or Argon2id — never MD5 or SHA-1.</li>
          <li>Rotate API keys and service credentials on a defined schedule (at minimum annually).</li>
        </ul>
      </div>
      <div class="pillar-card">
        <iconify-icon icon="lucide:lock" class="pillar-icon"></iconify-icon>
        <h3>Authorisation</h3>
        <p>Ensure that authenticated principals can only access the resources and actions they are explicitly permitted to use.</p>
        <ul class="pillar-list">
          <li>Apply the principle of least privilege — grant only the permissions required for each role.</li>
          <li>Use RBAC or ABAC for fine-grained access control; document all roles and their capabilities.</li>
          <li>Validate authorisation on every request, server-side — never trust client-side claims.</li>
          <li>Audit permission changes and review access grants quarterly.</li>
          <li>Use separate IAM roles per service; avoid wildcard policies in production.</li>
        </ul>
      </div>
      <div class="pillar-card">
        <iconify-icon icon="lucide:database" class="pillar-icon"></iconify-icon>
        <h3>Data Encryption</h3>
        <p>Protect data at rest and in transit to prevent unauthorised disclosure even if storage or network security is compromised.</p>
        <ul class="pillar-list">
          <li>Enforce TLS 1.2+ for all network communication; prefer TLS 1.3.</li>
          <li>Encrypt all database volumes and object storage buckets using AES-256.</li>
          <li>Use envelope encryption with a KMS for secrets and sensitive configuration values.</li>
          <li>Never log sensitive data (passwords, tokens, PII, payment info).</li>
          <li>Classify data by sensitivity and apply controls appropriate to each classification level.</li>
        </ul>
      </div>
      <div class="pillar-card">
        <iconify-icon icon="lucide:monitor" class="pillar-icon"></iconify-icon>
        <h3>Network Security</h3>
        <p>Reduce the attack surface by controlling what can communicate with your services at the network level.</p>
        <ul class="pillar-list">
          <li>Place services in private subnets; expose only what's necessary through a load balancer or API gateway.</li>
          <li>Use security groups and NACLs with deny-by-default postures.</li>
          <li>Enable VPC flow logs and alert on unexpected egress traffic.</li>
          <li>Use a WAF in front of all public-facing endpoints to block OWASP Top 10 attack vectors.</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- Compliance Frameworks -->
  <section class="section" id="compliance-frameworks">
    <h2 class="section-title">
      <iconify-icon icon="lucide:clipboard-check"></iconify-icon>
      Compliance Frameworks
    </h2>
    <p class="section-subtitle">CodeFoundry infrastructure and processes are designed to support the following industry-standard compliance frameworks. Customers inherit applicable controls as described in the Shared Responsibility Matrix.</p>
    <hr class="section-divider">
    <div class="compliance-grid">
      <div class="compliance-card">
        <span class="compliance-badge">SOC 2 Type II</span>
        <h3>Service Organization Control 2</h3>
        <p>Covers the Trust Services Criteria: Security, Availability, Confidentiality, Processing Integrity, and Privacy. CodeFoundry undergoes annual SOC 2 audits performed by an independent CPA firm.</p>
        <div class="compliance-controls">
          <span class="control-tag">Access Control</span>
          <span class="control-tag">Encryption</span>
          <span class="control-tag">Availability</span>
          <span class="control-tag">Incident Response</span>
          <span class="control-tag">Change Management</span>
        </div>
      </div>
      <div class="compliance-card">
        <span class="compliance-badge">GDPR</span>
        <h3>General Data Protection Regulation</h3>
        <p>EU regulation governing collection, processing, and storage of personal data. CodeFoundry acts as a Data Processor; customers are the Data Controller and must implement their own privacy notices and consent mechanisms.</p>
        <div class="compliance-controls">
          <span class="control-tag">Data Minimisation</span>
          <span class="control-tag">Right to Erasure</span>
          <span class="control-tag">DPA Templates</span>
          <span class="control-tag">Breach Notification</span>
        </div>
      </div>
      <div class="compliance-card">
        <span class="compliance-badge">HIPAA</span>
        <h3>Health Insurance Portability &amp; Accountability Act</h3>
        <p>US standard for protecting Protected Health Information (PHI). Customers processing PHI must sign a Business Associate Agreement (BAA) with CodeFoundry and implement required safeguards at the application layer.</p>
        <div class="compliance-controls">
          <span class="control-tag">PHI Encryption</span>
          <span class="control-tag">Audit Logs</span>
          <span class="control-tag">BAA</span>
          <span class="control-tag">Access Controls</span>
        </div>
      </div>
      <div class="compliance-card">
        <span class="compliance-badge">ISO 27001</span>
        <h3>Information Security Management</h3>
        <p>International standard specifying requirements for an Information Security Management System (ISMS). CodeFoundry's ISMS is certified to ISO 27001:2022 and covers all production systems and development processes.</p>
        <div class="compliance-controls">
          <span class="control-tag">Risk Management</span>
          <span class="control-tag">Asset Management</span>
          <span class="control-tag">Business Continuity</span>
          <span class="control-tag">Supplier Relations</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Secure Coding Practices -->
  <section class="section" id="secure-coding">
    <h2 class="section-title">
      <iconify-icon icon="lucide:code-2"></iconify-icon>
      Secure Coding Practices
    </h2>
    <p class="section-subtitle">Apply these practices throughout the software development lifecycle to prevent common vulnerabilities before they reach production.</p>
    <hr class="section-divider">
    <div class="practices-grid">
      <div class="practice-card">
        <h3><iconify-icon icon="lucide:filter"></iconify-icon>Input Validation &amp; Sanitisation</h3>
        <ul class="practice-list">
          <li>Validate all external input (headers, query params, body) against a strict allow-list schema.</li>
          <li>Use parameterised queries or an ORM — never construct SQL strings by concatenation.</li>
          <li>Encode output to the correct context (HTML, JS, CSS, URL) to prevent XSS.</li>
          <li>Reject, don't sanitise, files with unexpected MIME types or extensions.</li>
        </ul>
      </div>
      <div class="practice-card">
        <h3><iconify-icon icon="lucide:key-square"></iconify-icon>Secrets Management</h3>
        <ul class="practice-list">
          <li>Store all secrets in a dedicated secrets manager (AWS Secrets Manager, HashiCorp Vault).</li>
          <li>Never commit secrets to version control; use pre-commit hooks to scan for credentials.</li>
          <li>Rotate secrets automatically and invalidate old versions immediately after rotation.</li>
          <li>Audit every secret access event and alert on unusual access patterns.</li>
        </ul>
      </div>
      <div class="practice-card">
        <h3><iconify-icon icon="lucide:package-check"></iconify-icon>Dependency Security</h3>
        <ul class="practice-list">
          <li>Run SCA (Software Composition Analysis) tools in CI to flag known CVEs in dependencies.</li>
          <li>Pin dependency versions with a lockfile; review every dependency update via PR.</li>
          <li>Prefer well-maintained packages with recent releases and security advisories.</li>
          <li>Subscribe to vulnerability feeds (GitHub Advisory DB, NVD) for your dependency stack.</li>
        </ul>
      </div>
      <div class="practice-card">
        <h3><iconify-icon icon="lucide:scan-line"></iconify-icon>SAST &amp; Code Review</h3>
        <ul class="practice-list">
          <li>Integrate a SAST tool (Semgrep, SonarQube, CodeQL) into every PR build.</li>
          <li>Require security-focused code review for changes to authentication, session, or cryptography code.</li>
          <li>Use threat modelling (STRIDE, PASTA) when designing new features that handle sensitive data.</li>
          <li>Track and remediate security findings with the same SLA as production bugs.</li>
        </ul>
      </div>
      <div class="practice-card">
        <h3><iconify-icon icon="lucide:container"></iconify-icon>Container Security</h3>
        <ul class="practice-list">
          <li>Use minimal base images (distroless or Alpine); remove shells and package managers.</li>
          <li>Run containers as non-root users with read-only root filesystems.</li>
          <li>Scan container images for CVEs before pushing to a registry.</li>
          <li>Sign images with cosign and enforce signature verification at deploy time.</li>
        </ul>
      </div>
      <div class="practice-card">
        <h3><iconify-icon icon="lucide:scroll-text"></iconify-icon>Logging &amp; Audit Trails</h3>
        <ul class="practice-list">
          <li>Log authentication events (login, logout, failed attempts, MFA challenges) centrally.</li>
          <li>Immutably store audit logs for a minimum of 12 months (90 days hot, rest archived).</li>
          <li>Do not log sensitive data — mask tokens, passwords, and PII before writing to logs.</li>
          <li>Alert on anomalous log patterns: impossible travel, privilege escalation, bulk data access.</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- Vulnerability Management -->
  <section class="section" id="vulnerability-management">
    <h2 class="section-title">
      <iconify-icon icon="lucide:bug"></iconify-icon>
      Vulnerability Management
    </h2>
    <p class="section-subtitle">CodeFoundry uses a risk-based approach to prioritise and remediate vulnerabilities. The following SLA table defines maximum remediation timelines by severity.</p>
    <hr class="section-divider">
    <div class="table-wrapper" style="margin-bottom:32px;">
      <table class="vuln-table">
        <thead>
          <tr>
            <th>Severity</th>
            <th>CVSS Range</th>
            <th>Remediation SLA</th>
            <th>Examples</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="severity-badge sev-critical">Critical</span></td>
            <td>9.0 – 10.0</td>
            <td>24 hours</td>
            <td>Unauthenticated RCE, authentication bypass, plaintext credential exposure</td>
          </tr>
          <tr>
            <td><span class="severity-badge sev-high">High</span></td>
            <td>7.0 – 8.9</td>
            <td>7 days</td>
            <td>Authenticated RCE, SQL injection, broken access control to sensitive resources</td>
          </tr>
          <tr>
            <td><span class="severity-badge sev-medium">Medium</span></td>
            <td>4.0 – 6.9</td>
            <td>30 days</td>
            <td>Stored XSS, SSRF, insecure direct object references, outdated TLS ciphers</td>
          </tr>
          <tr>
            <td><span class="severity-badge sev-low">Low</span></td>
            <td>0.1 – 3.9</td>
            <td>90 days</td>
            <td>Verbose error messages, missing security headers, informational findings</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>

  <!-- Incident Response -->
  <section class="section" id="incident-response">
    <h2 class="section-title">
      <iconify-icon icon="lucide:siren"></iconify-icon>
      Incident Response
    </h2>
    <p class="section-subtitle">When a security incident is detected, follow this structured process to contain, eradicate, and recover from the event while preserving evidence.</p>
    <hr class="section-divider">
    <div class="warn-box">
      <iconify-icon icon="lucide:alert-triangle"></iconify-icon>
      <span>If you believe you have discovered a security vulnerability in CodeFoundry, please report it responsibly via our <strong>Responsible Disclosure Programme</strong> at <a href="mailto:security@codefoundry.io" style="color:var(--primary)">security@codefoundry.io</a>. Do not create public GitHub issues for security vulnerabilities.</span>
    </div>
    <div class="ir-timeline">
      <div class="ir-step">
        <div class="ir-step-body">
          <h3>1. Detection &amp; Triage <span class="ir-time">T+0</span></h3>
          <p>Confirm the incident is real and not a false positive. Assign a severity level and incident commander. Create a dedicated incident channel and begin timeline documentation immediately.</p>
        </div>
      </div>
      <div class="ir-step">
        <div class="ir-step-body">
          <h3>2. Containment <span class="ir-time">T+1h</span></h3>
          <p>Isolate affected systems to prevent lateral movement. Revoke compromised credentials, block malicious IPs, and snapshot affected instances for forensic analysis before any remediation.</p>
        </div>
      </div>
      <div class="ir-step">
        <div class="ir-step-body">
          <h3>3. Eradication <span class="ir-time">T+4h</span></h3>
          <p>Remove the root cause: patch the vulnerability, delete malicious code or accounts, and verify the attack vector is fully closed. Confirm with the security team before proceeding.</p>
        </div>
      </div>
      <div class="ir-step">
        <div class="ir-step-body">
          <h3>4. Recovery <span class="ir-time">T+8h</span></h3>
          <p>Restore services from known-good backups or redeploy from immutable infrastructure definitions. Verify functionality and monitor closely for any recurrence in the 24 hours post-recovery.</p>
        </div>
      </div>
      <div class="ir-step">
        <div class="ir-step-body">
          <h3>5. Post-Incident Review <span class="ir-time">T+72h</span></h3>
          <p>Conduct a blameless post-mortem. Document what happened, root cause, timeline, impact, and corrective actions. Publish a summary to affected stakeholders within the required notification window.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Next steps -->
  <section class="section" id="next-steps">
    <h2 class="section-title">
      <iconify-icon icon="lucide:arrow-right-circle"></iconify-icon>
      Related Documentation
    </h2>
    <hr class="section-divider">
    <div class="next-steps-grid">
      <a href="/Documentation/getting-started.php" class="next-step-link">
        <iconify-icon icon="lucide:book-open"></iconify-icon>
        Getting Started
      </a>
      <a href="/Documentation/api-reference.php" class="next-step-link">
        <iconify-icon icon="lucide:code-2"></iconify-icon>
        API Reference
      </a>
      <a href="/Documentation/cloud-solutions.php" class="next-step-link">
        <iconify-icon icon="lucide:cloud"></iconify-icon>
        Cloud Solutions
      </a>
      <a href="/Documentation/troubleshooting.php" class="next-step-link">
        <iconify-icon icon="lucide:wrench"></iconify-icon>
        Troubleshooting
      </a>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
