<?php
$page_title  = 'Training Resources - CodeFoundry';
$active_page = 'training';
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

    header {
      background: var(--navy);
      color: var(--text);
      padding: 0;
      position: sticky;
      top: 0;
      z-index: 1000;
      border-bottom: 1px solid #192746;
    }
    .nav {
      max-width: var(--maxwidth);
      margin: 0 auto;
      padding: 0 40px;
      min-height: var(--header-height);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .brand {
      display: flex;
      align-items: center;
      font-weight: 800;
      font-size: 22px;
      gap: 12px;
      letter-spacing: -0.5px;
    }
    .brand svg {
      width: 28px;
      height: 28px;
      background: var(--primary);
      border-radius: 6px;
      color: #092340;
      padding: 4px;
      margin-right: 4px;
      box-sizing: border-box;
    }
    .nav-menu {
      display: flex;
      gap: 28px;
      align-items: center;
    }
    .nav-link {
      color: var(--text-muted);
      text-decoration: none;
      font-weight: 500;
      font-size: 15px;
      transition: color .2s;
    }
    .nav-link:hover,
    .nav-link.active {
      color: var(--text);
    }
    .nav-actions {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .nav-btn {
      font-family: inherit;
      font-size: 15px;
      font-weight: 700;
      border: 0;
      border-radius: var(--button-radius);
      padding: 10px 18px;
      background: var(--navy-3);
      color: var(--text);
      outline: 0;
      cursor: pointer;
      transition: background .2s, color .2s;
    }
    .nav-btn.primary {
      background: var(--primary);
      color: var(--navy-2);
      font-weight: 700;
    }
    .nav-btn.primary:hover {
      background: var(--primary-hover);
    }
    .nav-btn.secondary {
      background: #fff;
      color: var(--navy);
    }
    .mobile-hamburger {
      display: none;
      background: none;
      border: none;
      color: var(--text);
      font-size: 29px;
      padding: 5px 10px;
      margin-left: 10px;
      cursor: pointer;
    }
    /* MOBILE NAV */
    .mobile-nav-overlay {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 100vw;
      background: var(--mobile-menu-bg);
      z-index: 9999;
      transition: opacity .23s, visibility .23s;
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: flex-end;
    }
    .mobile-nav-overlay.open {
      opacity: 1;
      visibility: visible;
      pointer-events: auto;
      transition: opacity .32s;
    }
    .mobile-nav-panel {
      background: var(--navy);
      height: 100%;
      width: 280px;
      padding: 20px;
      box-shadow: -4px 0 20px rgba(0,0,0,0.3);
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    .mobile-menu-close {
      background: none;
      border: none;
      color: var(--text);
      font-size: 28px;
      cursor: pointer;
      align-self: flex-end;
      padding: 5px;
    }
    .mobile-menu-links {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }
    .mobile-menu-actions {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-top: auto;
    }

    /* HERO SECTION */
    .hero-bg {
      background: linear-gradient(135deg, #0e1828 0%, #1a2942 100%);
      padding: 60px 20px;
    }
    .hero {
      max-width: var(--maxwidth);
      margin: 0 auto;
      text-align: center;
    }
    .hero-badge {
      background: var(--navy-3);
      color: var(--primary);
      font-weight: 700;
      font-size: 14px;
      padding: 8px 16px;
      border-radius: 20px;
      display: inline-block;
      margin-bottom: 20px;
    }
    .hero-title {
      font-size: 3rem;
      font-weight: 800;
      margin: 0 0 20px 0;
      line-height: 1.2;
    }
    .hero-desc {
      font-size: 1.2rem;
      color: var(--text-muted);
      max-width: 700px;
      margin: 0 auto 30px auto;
      line-height: 1.6;
    }

    /* SECTIONS */
    section {
      padding: 60px 20px;
      background: transparent;
    }
    .section-heading {
      max-width: var(--maxwidth);
      margin: 0 auto 42px auto;
      text-align: center;
    }
    .section-badge {
      background: var(--navy-3);
      color: var(--primary);
      font-weight: 700;
      font-size: 13px;
      padding: 6px 14px;
      border-radius: 20px;
      display: inline-block;
      margin-bottom: 12px;
      text-transform: uppercase;
    }
    .section-title {
      font-size: 2.5rem;
      font-weight: 800;
      margin: 0 0 16px 0;
    }
    .section-desc {
      color: var(--text-muted);
      font-size: 1.1rem;
      max-width: 700px;
      margin: 0 auto;
      line-height: 1.6;
    }

    /* CARD GRID */
    .card-grid {
      max-width: var(--maxwidth);
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 24px;
      padding: 0 20px;
    }
    .resource-card {
      background: var(--navy-3);
      border: 1px solid var(--border-color);
      border-radius: var(--card-radius);
      padding: 32px;
      transition: transform 0.2s, border-color 0.2s;
    }
    .resource-card:hover {
      transform: translateY(-4px);
      border-color: var(--primary);
    }
    .card-icon {
      font-size: 48px;
      color: var(--primary);
      margin-bottom: 20px;
      display: block;
    }
    .card-title {
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0 0 12px 0;
    }
    .card-desc {
      color: var(--text-muted);
      font-size: 1rem;
      line-height: 1.6;
      margin-bottom: 20px;
    }
    .feature-list {
      list-style: none;
      padding: 0;
      margin: 0 0 24px 0;
    }
    .feature-item {
      color: var(--text-muted);
      font-size: 15px;
      padding: 8px 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .feature-item iconify-icon {
      color: var(--primary);
      font-size: 18px;
    }
    .card-btn {
      display: inline-block;
      background: var(--primary);
      color: var(--navy-2);
      font-weight: 700;
      padding: 12px 24px;
      border-radius: var(--button-radius);
      text-align: center;
      transition: background 0.2s;
      width: 100%;
      box-sizing: border-box;
    }
    .card-btn:hover {
      background: var(--primary-hover);
    }

    /* SIMULATOR SECTION */
    .simulator-grid {
      max-width: var(--maxwidth);
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      padding: 0 20px;
    }
    .simulator-card {
      background: var(--navy-3);
      border: 1px solid var(--border-color);
      border-radius: var(--card-radius);
      padding: 24px;
      text-align: center;
      transition: transform 0.2s, border-color 0.2s;
    }
    .simulator-card:hover {
      transform: translateY(-4px);
      border-color: var(--primary);
    }
    .simulator-icon {
      font-size: 42px;
      color: var(--primary);
      margin-bottom: 16px;
    }
    .simulator-name {
      font-size: 1.2rem;
      font-weight: 700;
      margin: 0 0 8px 0;
    }
    .simulator-desc {
      color: var(--text-muted);
      font-size: 0.9rem;
      margin-bottom: 16px;
    }
    .simulator-btn {
      display: inline-block;
      background: var(--navy);
      border: 1px solid var(--primary);
      color: var(--primary);
      font-weight: 600;
      padding: 10px 20px;
      border-radius: var(--button-radius);
      transition: background 0.2s, color 0.2s;
    }
    .simulator-btn:hover {
      background: var(--primary);
      color: var(--navy-2);
    }

    /* QUIZ SECTION */
    .quiz-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 0 20px;
    }
    .quiz-card {
      background: var(--navy-3);
      border: 1px solid var(--border-color);
      border-radius: var(--card-radius);
      padding: 32px;
      margin-bottom: 20px;
    }
    .quiz-title {
      font-size: 1.3rem;
      font-weight: 700;
      margin: 0 0 12px 0;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .quiz-meta {
      display: flex;
      gap: 20px;
      margin-bottom: 16px;
      color: var(--text-muted);
      font-size: 0.9rem;
    }
    .quiz-meta span {
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .quiz-btn {
      display: inline-block;
      background: var(--primary);
      color: var(--navy-2);
      font-weight: 700;
      padding: 12px 28px;
      border-radius: var(--button-radius);
      transition: background 0.2s;
    }
    .quiz-btn:hover {
      background: var(--primary-hover);
    }

    /* GUIDES SECTION */
    .guides-grid {
      max-width: var(--maxwidth);
      margin: 0 auto;
      padding: 0 20px;
    }
    .guide-card {
      background: var(--navy-3);
      border: 1px solid var(--border-color);
      border-radius: var(--card-radius);
      padding: 28px;
      margin-bottom: 20px;
      display: flex;
      gap: 24px;
      align-items: flex-start;
      transition: border-color 0.2s;
    }
    .guide-card:hover {
      border-color: var(--primary);
    }
    .guide-number {
      background: var(--primary);
      color: var(--navy-2);
      font-weight: 800;
      font-size: 1.5rem;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .guide-content {
      flex: 1;
    }
    .guide-title {
      font-size: 1.4rem;
      font-weight: 700;
      margin: 0 0 12px 0;
    }
    .guide-desc {
      color: var(--text-muted);
      margin-bottom: 16px;
      line-height: 1.6;
    }
    .guide-topics {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 16px;
    }
    .topic-tag {
      background: var(--navy);
      color: var(--primary);
      font-size: 0.85rem;
      padding: 6px 12px;
      border-radius: 20px;
      font-weight: 600;
    }
    .guide-link {
      color: var(--primary);
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: color 0.2s;
    }
    .guide-link:hover {
      color: var(--primary-hover);
    }

    /* MODAL STYLES */
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(14, 24, 40, 0.95);
      z-index: 2000;
      overflow-y: auto;
      padding: 20px;
    }
    .modal-overlay.active {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .modal-content {
      background: var(--navy);
      border: 1px solid var(--border-color);
      border-radius: var(--card-radius);
      max-width: 900px;
      width: 100%;
      max-height: 90vh;
      overflow-y: auto;
      position: relative;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }
    .modal-header {
      padding: 24px 28px;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      background: var(--navy);
      z-index: 10;
    }
    .modal-title {
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0;
    }
    .modal-close {
      background: transparent;
      border: none;
      color: var(--text-muted);
      font-size: 24px;
      cursor: pointer;
      padding: 4px;
      display: flex;
      align-items: center;
      transition: color 0.2s;
    }
    .modal-close:hover {
      color: var(--text);
    }
    .modal-body {
      padding: 28px;
    }

    /* Quiz Modal Specific */
    .quiz-progress {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding: 12px 16px;
      background: var(--navy-3);
      border-radius: 8px;
    }
    .quiz-question {
      margin-bottom: 24px;
    }
    .quiz-question-text {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 20px;
    }
    .quiz-options {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .quiz-option {
      background: var(--navy-3);
      border: 2px solid var(--border-color);
      border-radius: 8px;
      padding: 16px;
      cursor: pointer;
      transition: all 0.2s;
    }
    .quiz-option:hover {
      border-color: var(--primary);
      background: var(--navy-2);
    }
    .quiz-option.selected {
      border-color: var(--primary);
      background: rgba(24, 179, 255, 0.1);
    }
    .quiz-option.correct {
      border-color: #10b981;
      background: rgba(16, 185, 129, 0.1);
    }
    .quiz-option.incorrect {
      border-color: #ef4444;
      background: rgba(239, 68, 68, 0.1);
    }
    .quiz-navigation {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 24px;
      padding-top: 20px;
      border-top: 1px solid var(--border-color);
    }
    .quiz-results {
      text-align: center;
      padding: 40px 20px;
    }
    .quiz-score {
      font-size: 3rem;
      font-weight: 800;
      color: var(--primary);
      margin: 20px 0;
    }
    .quiz-result-details {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 16px;
      margin: 30px 0;
    }
    .quiz-result-stat {
      background: var(--navy-3);
      padding: 20px;
      border-radius: 8px;
    }
    .quiz-result-stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary);
    }
    .quiz-result-stat-label {
      color: var(--text-muted);
      font-size: 0.9rem;
      margin-top: 4px;
    }

    /* QUIZ LEVEL SELECTOR */
    .quiz-level-selector {
      padding: 16px 0 8px 0;
    }
    .quiz-level-selector h3 {
      font-size: 1.1rem;
      font-weight: 600;
      margin: 0 0 6px 0;
    }
    .quiz-level-selector p {
      color: var(--text-muted);
      font-size: 0.9rem;
      margin: 0 0 20px 0;
    }
    .level-options {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .level-option {
      background: var(--navy-3);
      border: 2px solid var(--border-color);
      border-radius: 10px;
      padding: 18px 20px;
      cursor: pointer;
      transition: all 0.2s;
      text-align: left;
    }
    .level-option:hover {
      border-color: var(--primary);
      background: rgba(24, 179, 255, 0.05);
    }
    .level-option-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 4px;
    }
    .level-badge {
      font-size: 0.75rem;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 20px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .level-badge.beginner  { background: rgba(16,185,129,0.15); color: #10b981; }
    .level-badge.intermediate { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .level-badge.advanced  { background: rgba(239,68,68,0.15); color: #ef4444; }
    .level-option-name {
      font-weight: 700;
      font-size: 1rem;
    }
    .level-option-desc {
      color: var(--text-muted);
      font-size: 0.875rem;
    }
    .quiz-level-pill {
      font-size: 0.75rem;
      font-weight: 700;
      padding: 2px 9px;
      border-radius: 20px;
      text-transform: uppercase;
    }
    .quiz-level-pill.beginner     { background: rgba(16,185,129,0.15); color: #10b981; }
    .quiz-level-pill.intermediate { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .quiz-level-pill.advanced     { background: rgba(239,68,68,0.15); color: #ef4444; }

    /* Guide Modal Specific */
    .guide-modal-content h3 {
      color: var(--primary);
      margin-top: 24px;
      margin-bottom: 12px;
    }
    .guide-modal-content h4 {
      margin-top: 20px;
      margin-bottom: 10px;
    }
    .guide-modal-content p {
      line-height: 1.7;
      margin-bottom: 16px;
      color: var(--text-muted);
    }
    .guide-modal-content ul, .guide-modal-content ol {
      margin: 16px 0;
      padding-left: 24px;
      line-height: 1.8;
    }
    .guide-modal-content li {
      margin-bottom: 8px;
      color: var(--text-muted);
    }
    .guide-modal-content code {
      background: var(--navy-3);
      padding: 2px 6px;
      border-radius: 4px;
      font-family: 'Courier New', monospace;
      font-size: 0.9em;
      color: var(--primary);
    }
    .guide-modal-content pre {
      background: var(--navy-3);
      padding: 16px;
      border-radius: 8px;
      overflow-x: auto;
      margin: 16px 0;
    }
    .guide-modal-content pre code {
      background: transparent;
      padding: 0;
    }

    /* Simulator Modal Specific */
    .simulator-interface {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .simulator-editor {
      background: var(--navy-3);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      min-height: 300px;
    }
    .simulator-toolbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 16px;
      background: var(--navy-2);
      border-bottom: 1px solid var(--border-color);
    }
    .simulator-code {
      padding: 16px;
      font-family: 'Courier New', monospace;
      font-size: 14px;
      line-height: 1.5;
      min-height: 250px;
      resize: vertical;
      background: transparent;
      border: none;
      color: var(--text);
      width: 100%;
      outline: none;
    }
    .simulator-output {
      background: var(--navy-3);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      padding: 16px;
      min-height: 150px;
      font-family: 'Courier New', monospace;
      font-size: 13px;
      line-height: 1.6;
      color: #10b981;
      white-space: pre-wrap;
      word-wrap: break-word;
    }
    .simulator-actions {
      display: flex;
      gap: 12px;
    }
    .simulator-btn-primary {
      background: var(--primary);
      color: var(--navy-2);
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.2s;
    }
    .simulator-btn-primary:hover {
      background: var(--primary-hover);
    }
    .simulator-btn-secondary {
      background: var(--navy-3);
      color: var(--text);
      border: 1px solid var(--border-color);
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
    }
    .simulator-btn-secondary:hover {
      border-color: var(--primary);
      background: var(--navy-2);
    }

    /* CERTIFICATION SECTION */
    .cert-grid {
      max-width: var(--maxwidth);
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 24px;
      padding: 0 20px;
    }
    .cert-card {
      background: linear-gradient(135deg, var(--navy-3) 0%, var(--navy) 100%);
      border: 2px solid var(--border-color);
      border-radius: var(--card-radius);
      padding: 32px;
      transition: transform 0.2s, border-color 0.2s;
    }
    .cert-card:hover {
      transform: translateY(-4px);
      border-color: var(--primary);
    }
    .cert-badge {
      font-size: 60px;
      color: var(--primary);
      margin-bottom: 20px;
      display: block;
    }
    .cert-level {
      background: var(--primary);
      color: var(--navy-2);
      font-weight: 700;
      font-size: 0.85rem;
      padding: 6px 12px;
      border-radius: 20px;
      display: inline-block;
      margin-bottom: 12px;
    }
    .cert-title {
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0 0 12px 0;
    }
    .cert-desc {
      color: var(--text-muted);
      font-size: 0.95rem;
      margin-bottom: 20px;
      line-height: 1.6;
    }
    .cert-stats {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 24px;
    }
    .cert-stat {
      text-align: center;
    }
    .cert-stat-value {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--primary);
      display: block;
    }
    .cert-stat-label {
      font-size: 0.85rem;
      color: var(--text-muted);
    }

    /* FOOTER */
    footer {
      background: var(--navy);
      border-top: 1px solid var(--border-color);
      padding: 50px 20px 30px 20px;
      margin-top: 80px;
    }
    .footer-content {
      max-width: var(--maxwidth);
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 40px;
      margin-bottom: 30px;
    }
    .footer-col-title {
      font-weight: 700;
      font-size: 16px;
      margin-bottom: 16px;
      color: var(--text);
    }
    .footer-links {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .footer-links li {
      margin-bottom: 12px;
    }
    .footer-link {
      color: var(--text-muted);
      font-size: 15px;
      transition: color .2s;
    }
    .footer-link:hover {
      color: var(--primary);
    }
    .footer-bottom {
      text-align: center;
      color: var(--text-subtle);
      font-size: 14px;
      padding-top: 30px;
      border-top: 1px solid var(--border-color);
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {
      .nav-menu, .nav-actions {display: none;}
      .mobile-hamburger {display: inline-block;}
    }
    @media (max-width: 768px) {
      .hero-title { font-size: 2.2rem; }
      .section-title { font-size: 2rem; }
      .card-grid, .simulator-grid, .cert-grid {
        grid-template-columns: 1fr;
      }
      .guide-card {
        flex-direction: column;
      }
    }
PAGECSS;
$page_scripts = <<<'PAGEJS'
// Mobile menu functionality
  const mobileMenuBtn = document.getElementById('mobileMenuBtn');
  const mobileNav = document.getElementById('mobileNav');
  const closeMobileNavBtn = document.getElementById('closeMobileNav');

  mobileMenuBtn?.addEventListener('click', () => {
    mobileNav?.classList.add('open');
  });

  closeMobileNavBtn?.addEventListener('click', () => {
    mobileNav?.classList.remove('open');
  });

  function closeMobileNav() {
    mobileNav?.classList.remove('open');
  }

  // Close menu when clicking outside
  mobileNav?.addEventListener('click', (e) => {
    if (e.target === mobileNav) {
      mobileNav.classList.remove('open');
    }
  });

  // ===== QUIZ DATA =====
  const quizData = {
    'javascript-fundamentals': {
      title: 'JavaScript Fundamentals',
      levels: {
        beginner: {
          label: 'Beginner',
          description: 'Core JavaScript concepts and basic syntax',
          questions: [
            { question: 'What is the correct way to declare a variable in JavaScript?', options: ['variable x = 5;', 'let x = 5;', 'v x = 5;', 'dim x = 5;'], correct: 1 },
            { question: 'Which of the following is NOT a JavaScript data type?', options: ['String', 'Boolean', 'Float', 'Undefined'], correct: 2 },
            { question: 'What does "===" check in JavaScript?', options: ['Only value', 'Only type', 'Both value and type', 'Neither value nor type'], correct: 2 },
            { question: 'Which method is used to add an element to the end of an array?', options: ['push()', 'pop()', 'shift()', 'unshift()'], correct: 0 },
            { question: 'What is the purpose of the "use strict" directive?', options: ['Improves performance', 'Enables strict mode', 'Compresses code', 'Adds security'], correct: 1 }
          ]
        },
        intermediate: {
          label: 'Intermediate',
          description: 'ES6+, closures, and async programming',
          questions: [
            { question: 'What is a closure in JavaScript?', options: ['A loop construct', 'A function retaining access to its outer scope variables', 'A CSS technique', 'A data structure'], correct: 1 },
            { question: 'What does `this` refer to inside an arrow function?', options: ['The arrow function itself', 'The window object', 'The enclosing lexical context', 'undefined'], correct: 2 },
            { question: 'Which array method returns elements that pass a test?', options: ['map()', 'reduce()', 'filter()', 'find()'], correct: 2 },
            { question: 'What is the difference between `null` and `undefined`?', options: ['They are identical', 'null is explicitly set; undefined is uninitialized', 'undefined is explicitly set; null is uninitialized', 'Both are the same as false'], correct: 1 },
            { question: 'What is a Promise in JavaScript?', options: ['A syntax for declaring variables', 'An object representing eventual completion or failure of an async operation', 'A loop mechanism', 'A class decorator'], correct: 1 }
          ]
        },
        advanced: {
          label: 'Advanced',
          description: 'Deep internals, design patterns, optimization',
          questions: [
            { question: 'What is the output of `typeof null`?', options: ['"null"', '"object"', '"undefined"', '"boolean"'], correct: 1 },
            { question: 'What does the JavaScript event loop do?', options: ['Handles synchronous code only', 'Processes the call stack and callback queue to manage async operations', 'Compiles JavaScript to machine code', 'Manages memory allocation'], correct: 1 },
            { question: 'What is the difference between `call` and `bind`?', options: ['They are identical', 'call invokes immediately; bind returns a new function', 'bind invokes immediately; call returns a new function', 'Neither invokes the function'], correct: 1 },
            { question: 'What is a generator function?', options: ['A function that creates objects', 'A function that can pause and resume execution using yield', 'A function that runs only once', 'A function with no return value'], correct: 1 },
            { question: 'What does `Object.freeze()` do?', options: ['Copies an object', 'Removes all properties', 'Prevents adding, removing, or modifying properties', 'Converts object to JSON'], correct: 2 }
          ]
        }
      }
    },
    'react-modern-web': {
      title: 'React & Modern Web Development',
      levels: {
        beginner: {
          label: 'Beginner',
          description: 'React fundamentals and core concepts',
          questions: [
            { question: 'What is a React Hook?', options: ['A JavaScript library', 'A function that lets you use state in functional components', 'A CSS framework', 'A routing method'], correct: 1 },
            { question: 'Which hook is used for side effects in React?', options: ['useState', 'useEffect', 'useContext', 'useReducer'], correct: 1 },
            { question: 'What does JSX stand for?', options: ['JavaScript XML', 'Java Syntax Extension', 'JavaScript Extra', 'JSON Extension'], correct: 0 },
            { question: 'How do you pass data from parent to child component?', options: ['Using state', 'Using props', 'Using context', 'Using refs'], correct: 1 },
            { question: 'What is the virtual DOM?', options: ['A physical representation of the DOM', 'A lightweight copy of the actual DOM', 'A debugging tool', 'A database'], correct: 1 }
          ]
        },
        intermediate: {
          label: 'Intermediate',
          description: 'Hooks, context, and performance patterns',
          questions: [
            { question: 'What is the React Context API used for?', options: ['Styling components', 'Managing server state', 'Sharing state across components without prop drilling', 'Handling routing'], correct: 2 },
            { question: 'What does `useMemo` do?', options: ['Memoizes a callback function', 'Memoizes an expensive computed value', 'Fetches data from an API', 'Creates a ref'], correct: 1 },
            { question: 'What is the `key` prop used for in lists?', options: ['Styling list items', 'Helping React identify which items changed', 'Setting item order', 'Assigning event handlers'], correct: 1 },
            { question: 'What is React.lazy() used for?', options: ['Lazy state initialization', 'Code splitting and lazy loading of components', 'Delayed rendering', 'Caching API responses'], correct: 1 },
            { question: 'What does `useCallback` return?', options: ['A memoized value', 'A memoized callback function', 'A new component', 'A ref object'], correct: 1 }
          ]
        },
        advanced: {
          label: 'Advanced',
          description: 'Reconciliation, portals, and advanced patterns',
          questions: [
            { question: 'What is React reconciliation?', options: ['Fetching remote data', "React's diffing algorithm for efficiently updating the DOM", 'Combining multiple components', 'Managing global state'], correct: 1 },
            { question: 'What are React portals?', options: ['Navigation links', 'A way to render children outside the parent DOM node', 'Error boundaries', 'Context providers'], correct: 1 },
            { question: 'What does React.memo() do?', options: ['Saves component state', 'Prevents unnecessary re-renders of functional components', 'Creates a memoized ref', 'Delays hydration'], correct: 1 },
            { question: 'When would you use useReducer over useState?', options: ['For simple boolean flags', 'For complex state logic with multiple sub-values', 'For accessing the DOM', 'For server-side data'], correct: 1 },
            { question: 'What is React Suspense used for?', options: ['Canceling renders', 'Declaratively handling async loading states and code splitting', 'Error recovery', 'Batching state updates'], correct: 1 }
          ]
        }
      }
    },
    'backend-api': {
      title: 'Backend & API Development',
      levels: {
        beginner: {
          label: 'Beginner',
          description: 'REST, HTTP methods, and API fundamentals',
          questions: [
            { question: 'What does REST stand for?', options: ['Representational State Transfer', 'Remote Execution Service Technology', 'Rapid Enterprise System Transfer', 'Reliable Endpoint Service Technology'], correct: 0 },
            { question: 'Which HTTP method is used to update a resource?', options: ['GET', 'POST', 'PUT', 'DELETE'], correct: 2 },
            { question: 'What is GraphQL?', options: ['A database', 'A query language for APIs', 'A programming language', 'A web framework'], correct: 1 },
            { question: 'What is JWT used for?', options: ['Database queries', 'Authentication and authorization', 'Styling', 'Routing'], correct: 1 },
            { question: 'Which status code indicates a successful GET request?', options: ['200', '201', '204', '404'], correct: 0 }
          ]
        },
        intermediate: {
          label: 'Intermediate',
          description: 'Middleware, idempotency, and security',
          questions: [
            { question: 'What is middleware in Express.js?', options: ['A database connector', 'Functions that execute during the request-response cycle', 'A routing algorithm', 'A templating engine'], correct: 1 },
            { question: 'What is the difference between PUT and PATCH?', options: ['They are identical', 'PUT replaces the entire resource; PATCH partially updates it', 'PATCH replaces the entire resource; PUT partially updates it', 'PUT creates; PATCH deletes'], correct: 1 },
            { question: 'What does idempotent mean for HTTP methods?', options: ['The method is encrypted', 'Multiple identical requests produce the same result as one', 'The method requires authentication', 'The response is always cached'], correct: 1 },
            { question: 'What is rate limiting used for?', options: ['Compressing responses', 'Controlling the number of requests a client can make in a time window', 'Caching data', 'Balancing load'], correct: 1 },
            { question: 'What is CORS?', options: ['A database protocol', 'A policy controlling cross-origin HTTP requests', 'An authentication standard', 'A data format'], correct: 1 }
          ]
        },
        advanced: {
          label: 'Advanced',
          description: 'gRPC, event sourcing, CQRS, and API gateways',
          questions: [
            { question: 'What is gRPC?', options: ['A caching system', 'A high-performance RPC framework using Protocol Buffers', 'A GraphQL variant', 'A REST extension'], correct: 1 },
            { question: 'What is the N+1 query problem?', options: ['Using N database indices', 'Executing N additional queries for each item in a result set', 'Having N API versions', 'Sending N duplicate requests'], correct: 1 },
            { question: 'What is event sourcing?', options: ['Logging HTTP events', 'Storing state changes as an immutable sequence of events', 'Event-driven CSS', 'DOM event capture'], correct: 1 },
            { question: 'What is CQRS?', options: ['A CSS framework', 'Command Query Responsibility Segregation — separating read and write models', 'A container registry', 'A CI/CD strategy'], correct: 1 },
            { question: 'What does an API gateway provide?', options: ['Database migrations', 'A single entry point for clients to access microservices', 'CSS optimization', 'Static file serving'], correct: 1 }
          ]
        }
      }
    },
    'cloud-devops': {
      title: 'Cloud Architecture & DevOps',
      levels: {
        beginner: {
          label: 'Beginner',
          description: 'CI/CD, Docker, Kubernetes, and cloud basics',
          questions: [
            { question: 'What does CI/CD stand for?', options: ['Continuous Integration/Continuous Deployment', 'Cloud Integration/Cloud Deployment', 'Code Integration/Code Development', 'Container Integration/Container Delivery'], correct: 0 },
            { question: 'What is Docker used for?', options: ['Version control', 'Containerization', 'Database management', 'UI design'], correct: 1 },
            { question: 'What is Kubernetes?', options: ['A programming language', 'A container orchestration platform', 'A database', 'A web server'], correct: 1 },
            { question: 'Which AWS service is used for object storage?', options: ['EC2', 'S3', 'Lambda', 'RDS'], correct: 1 },
            { question: 'What is Infrastructure as Code (IaC)?', options: ['Writing infrastructure configurations in code', 'Building infrastructure manually', 'A cloud provider', 'A deployment strategy'], correct: 0 }
          ]
        },
        intermediate: {
          label: 'Intermediate',
          description: 'Volumes, namespaces, scaling, and deployments',
          questions: [
            { question: 'What is a Docker volume?', options: ['A Docker network mode', "Persistent storage that exists outside a container's lifecycle", 'A base image layer', 'A container restart policy'], correct: 1 },
            { question: 'What is a Kubernetes namespace?', options: ['A DNS record', 'A virtual cluster used to isolate and organize resources', 'A storage class', 'A container image tag'], correct: 1 },
            { question: 'What is blue-green deployment?', options: ['A color-coded branching strategy', 'Running two identical production environments to enable instant traffic switching', 'A Kubernetes scheduling policy', 'A Docker networking mode'], correct: 1 },
            { question: 'What does a load balancer do?', options: ['Compresses static assets', 'Distributes incoming traffic across multiple servers', 'Manages database connections', 'Scans for vulnerabilities'], correct: 1 },
            { question: 'What is serverless computing?', options: ['Running code without any servers', 'Executing code without managing or provisioning servers', 'A bare-metal hosting model', 'A Kubernetes node type'], correct: 1 }
          ]
        },
        advanced: {
          label: 'Advanced',
          description: 'Service mesh, Helm, GitOps, and chaos engineering',
          questions: [
            { question: 'What is a service mesh?', options: ['A Docker network', 'Infrastructure layer managing service-to-service communication in microservices', 'A Kubernetes Ingress type', 'A load-balancing algorithm'], correct: 1 },
            { question: 'What is Helm in Kubernetes?', options: ['A monitoring tool', 'A package manager for Kubernetes applications', 'A secret manager', 'A network policy engine'], correct: 1 },
            { question: 'What is GitOps?', options: ['A Git branching strategy', 'Using Git as the single source of truth for declarative infrastructure and application delivery', 'A CI/CD provider', 'A container registry'], correct: 1 },
            { question: 'What is the difference between vertical and horizontal scaling?', options: ['They are the same', 'Vertical adds more power to existing nodes; horizontal adds more nodes', 'Horizontal adds power; vertical adds nodes', 'Only horizontal scaling is supported in cloud'], correct: 1 },
            { question: 'What is chaos engineering?', options: ['Writing poorly structured code', 'Intentionally introducing failures into production to test system resilience', 'Random deployment strategies', 'Uncontrolled infrastructure changes'], correct: 1 }
          ]
        }
      }
    },
    'security-practices': {
      title: 'Security Best Practices',
      levels: {
        beginner: {
          label: 'Beginner',
          description: 'Common vulnerabilities and secure fundamentals',
          questions: [
            { question: 'What does XSS stand for?', options: ['Extra Style Sheets', 'Cross-Site Scripting', 'XML Security Standard', 'Extended Server Security'], correct: 1 },
            { question: 'What is SQL injection?', options: ['A database optimization technique', 'A security vulnerability where malicious SQL code is inserted', 'A data backup method', 'A query optimization tool'], correct: 1 },
            { question: 'What is HTTPS?', options: ['HTTP with security', 'Hypertext Transfer Protocol Secure', 'High-level Transfer Protocol', 'Host Transfer Protocol System'], correct: 1 },
            { question: 'What is the purpose of CORS?', options: ['To compress data', 'To control cross-origin resource sharing', 'To encrypt passwords', 'To manage cookies'], correct: 1 },
            { question: 'What is hashing used for in security?', options: ['Compressing files', 'Storing passwords securely', 'Speeding up queries', 'Managing sessions'], correct: 1 }
          ]
        },
        intermediate: {
          label: 'Intermediate',
          description: 'OWASP, 2FA, penetration testing, and CSRF',
          questions: [
            { question: 'What is OWASP?', options: ['A programming language', 'The Open Web Application Security Project — a foundation for software security', 'An AWS service', 'A JavaScript framework'], correct: 1 },
            { question: 'What is two-factor authentication (2FA)?', options: ['Using two different passwords', 'Requiring two distinct verification factors to prove identity', 'Hashing a password twice', 'A type of OAuth flow'], correct: 1 },
            { question: 'What is a penetration test?', options: ['Performance benchmarking', 'An authorized simulated attack to identify security vulnerabilities', 'A database query test', 'A load balancing test'], correct: 1 },
            { question: 'What is the principle of least privilege?', options: ['Giving administrators full access', 'Granting only the minimum permissions required to perform a task', 'Disabling all authentication', 'Using the shortest passwords'], correct: 1 },
            { question: 'What is a CSRF attack?', options: ['Cross-Site Resource Sharing', 'Cross-Site Request Forgery — tricking a browser into making unauthorized requests', 'Cross-Server Resource Failure', 'Content Security Response Framework'], correct: 1 }
          ]
        },
        advanced: {
          label: 'Advanced',
          description: 'Zero-days, OAuth 2.0, timing attacks, and defense in depth',
          questions: [
            { question: 'What is a zero-day vulnerability?', options: ['A minor software bug', 'A security flaw unknown to the vendor with no available patch', 'An expired SSL certificate', 'A firewall misconfiguration'], correct: 1 },
            { question: 'What is certificate pinning?', options: ['Permanently storing cookies', 'Associating a host with a specific expected certificate or public key', 'A JWT validation technique', 'An HTTPS redirect rule'], correct: 1 },
            { question: 'What is OAuth 2.0?', options: ['An encryption algorithm', 'An authorization framework allowing third-party limited access to user resources', 'A password hashing scheme', 'A session storage standard'], correct: 1 },
            { question: 'What is a timing attack?', options: ['A DDoS technique', 'Exploiting measurable time differences in cryptographic operations to infer secrets', 'A social engineering approach', 'An SQL injection variant'], correct: 1 },
            { question: 'What is defense in depth?', options: ['Using the strongest firewall', 'Applying multiple independent layers of security controls', 'Encrypting only sensitive data', 'Single sign-on'], correct: 1 }
          ]
        }
      }
    }
  };  // ===== GUIDE CONTENT =====
  const guideContent = {
    'fullstack-web': {
      title: 'Building a Full-Stack Web Application',
      content: `
        <h3>Introduction</h3>
        <p>This comprehensive guide walks you through building a modern full-stack web application using React, Node.js, and MongoDB. You'll learn how to create a scalable architecture, implement authentication, and deploy your application to production.</p>
        
        <h3>Prerequisites</h3>
        <ul>
          <li>Basic knowledge of JavaScript and ES6+ features</li>
          <li>Understanding of HTML and CSS</li>
          <li>Node.js and npm installed on your system</li>
          <li>Basic command line proficiency</li>
        </ul>
        
        <h3>Part 1: Setting Up the Project</h3>
        <h4>Backend Setup</h4>
        <p>First, let's create the backend structure:</p>
        <pre><code>mkdir fullstack-app
cd fullstack-app
mkdir backend frontend
cd backend
npm init -y
npm install express mongoose dotenv cors bcryptjs jsonwebtoken</code></pre>
        
        <h4>Frontend Setup</h4>
        <p>Create a React application:</p>
        <pre><code>cd ../frontend
npx create-react-app .
npm install axios react-router-dom</code></pre>
        
        <h3>Part 2: Building the Backend API</h3>
        <p>Create a RESTful API with Express.js that handles user authentication, data management, and business logic. Implement middleware for error handling, authentication, and validation.</p>
        
        <h4>Key Components:</h4>
        <ul>
          <li>Express server configuration</li>
          <li>MongoDB connection with Mongoose</li>
          <li>User authentication with JWT</li>
          <li>RESTful API endpoints</li>
          <li>Error handling middleware</li>
        </ul>
        
        <h3>Part 3: Frontend Development</h3>
        <p>Build a responsive React frontend with component-based architecture. Implement state management, routing, and API integration.</p>
        
        <h4>Key Features:</h4>
        <ul>
          <li>Component structure and hierarchy</li>
          <li>React Router for navigation</li>
          <li>API integration with Axios</li>
          <li>Form handling and validation</li>
          <li>State management with hooks</li>
        </ul>
        
        <h3>Part 4: Authentication & Security</h3>
        <p>Implement secure user authentication using JWT tokens, password hashing with bcrypt, and protected routes on both frontend and backend.</p>
        
        <h3>Part 5: Deployment</h3>
        <p>Deploy your application to production using platforms like Heroku, Vercel, or AWS. Set up environment variables, configure databases, and implement CI/CD pipelines.</p>
        
        <h3>Best Practices</h3>
        <ul>
          <li>Use environment variables for sensitive data</li>
          <li>Implement proper error handling</li>
          <li>Write clean, maintainable code</li>
          <li>Add comprehensive testing</li>
          <li>Document your API endpoints</li>
        </ul>
        
        <h3>Conclusion</h3>
        <p>By following this guide, you've built a complete full-stack application with modern technologies. Continue learning by exploring advanced topics like WebSockets, caching, and microservices.</p>
      `
    },
    'microservices': {
      title: 'Microservices Architecture Implementation',
      content: `
        <h3>Introduction</h3>
        <p>Learn how to design and implement a scalable microservices architecture using Docker, Kubernetes, and modern DevOps practices.</p>
        
        <h3>Understanding Microservices</h3>
        <p>Microservices architecture breaks down applications into small, independent services that communicate through APIs. Each service handles a specific business capability and can be developed, deployed, and scaled independently.</p>
        
        <h3>Key Principles</h3>
        <ul>
          <li>Single Responsibility: Each service focuses on one business capability</li>
          <li>Independence: Services are loosely coupled and independently deployable</li>
          <li>Decentralization: Distributed data management and decision-making</li>
          <li>Resilience: Graceful degradation when services fail</li>
        </ul>
        
        <h3>Architecture Components</h3>
        <h4>1. API Gateway</h4>
        <p>Acts as the entry point for all client requests. Routes requests to appropriate microservices and handles cross-cutting concerns.</p>
        
        <h4>2. Service Registry</h4>
        <p>Maintains a directory of available service instances for dynamic service discovery.</p>
        
        <h4>3. Message Queue</h4>
        <p>Enables asynchronous communication between services using technologies like RabbitMQ or Apache Kafka.</p>
        
        <h3>Implementation Steps</h3>
        <h4>Step 1: Identify Service Boundaries</h4>
        <p>Break down your monolith into logical services based on business domains and capabilities.</p>
        
        <h4>Step 2: Containerize Services</h4>
        <p>Use Docker to containerize each microservice:</p>
        <pre><code>FROM node:16-alpine
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
EXPOSE 3000
CMD ["npm", "start"]</code></pre>
        
        <h4>Step 3: Orchestrate with Kubernetes</h4>
        <p>Deploy and manage containers using Kubernetes for scaling, load balancing, and self-healing.</p>
        
        <h3>Inter-Service Communication</h3>
        <ul>
          <li>Synchronous: REST APIs, gRPC</li>
          <li>Asynchronous: Message queues, Event-driven architecture</li>
        </ul>
        
        <h3>Data Management</h3>
        <p>Each microservice should have its own database to maintain independence. Use the Database-per-Service pattern.</p>
        
        <h3>Observability</h3>
        <p>Implement comprehensive logging, monitoring, and tracing:</p>
        <ul>
          <li>Centralized logging with ELK stack</li>
          <li>Metrics with Prometheus and Grafana</li>
          <li>Distributed tracing with Jaeger</li>
        </ul>
        
        <h3>Best Practices</h3>
        <ul>
          <li>Design for failure and implement circuit breakers</li>
          <li>Use API versioning</li>
          <li>Implement health checks</li>
          <li>Secure service-to-service communication</li>
          <li>Automate testing and deployment</li>
        </ul>
      `
    },
    'cicd-pipeline': {
      title: 'CI/CD Pipeline Setup',
      content: `
        <h3>Introduction</h3>
        <p>Continuous Integration and Continuous Deployment (CI/CD) automate the software delivery process, enabling teams to deliver high-quality code faster and more reliably.</p>
        
        <h3>What is CI/CD?</h3>
        <p><strong>Continuous Integration (CI):</strong> Automatically build and test code changes as developers commit them to version control.</p>
        <p><strong>Continuous Deployment (CD):</strong> Automatically deploy tested code changes to production environments.</p>
        
        <h3>Benefits</h3>
        <ul>
          <li>Faster time to market</li>
          <li>Reduced manual errors</li>
          <li>Improved code quality</li>
          <li>Better collaboration</li>
          <li>Frequent, reliable releases</li>
        </ul>
        
        <h3>Pipeline Stages</h3>
        <h4>1. Source Stage</h4>
        <p>Triggered when code is pushed to version control (Git). The pipeline pulls the latest code.</p>
        
        <h4>2. Build Stage</h4>
        <p>Compile source code, install dependencies, and create build artifacts.</p>
        <pre><code>- name: Build
  run: |
    npm install
    npm run build</code></pre>
        
        <h4>3. Test Stage</h4>
        <p>Run automated tests including unit tests, integration tests, and end-to-end tests.</p>
        <pre><code>- name: Test
  run: |
    npm run test:unit
    npm run test:integration</code></pre>
        
        <h4>4. Security Scan</h4>
        <p>Perform security vulnerability scans and code quality checks.</p>
        
        <h4>5. Deploy Stage</h4>
        <p>Deploy to staging or production environments based on branch and test results.</p>
        
        <h3>GitHub Actions Example</h3>
        <pre><code>name: CI/CD Pipeline

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main ]

jobs:
  build-and-test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup Node.js
        uses: actions/setup-node@v2
        with:
          node-version: '16'
      - name: Install dependencies
        run: npm install
      - name: Run tests
        run: npm test
      - name: Build
        run: npm run build
      
  deploy:
    needs: build-and-test
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'
    steps:
      - name: Deploy to Production
        run: echo "Deploying to production..."</code></pre>
        
        <h3>Best Practices</h3>
        <ul>
          <li>Keep builds fast (under 10 minutes)</li>
          <li>Fail fast with early test execution</li>
          <li>Use caching to speed up builds</li>
          <li>Maintain separate staging and production environments</li>
          <li>Implement proper rollback mechanisms</li>
          <li>Monitor pipeline performance</li>
          <li>Use environment variables for secrets</li>
        </ul>
        
        <h3>Tools Comparison</h3>
        <ul>
          <li><strong>GitHub Actions:</strong> Integrated with GitHub, easy setup</li>
          <li><strong>Jenkins:</strong> Highly customizable, self-hosted</li>
          <li><strong>GitLab CI:</strong> Built into GitLab, powerful features</li>
          <li><strong>CircleCI:</strong> Fast builds, good for Docker</li>
        </ul>
      `
    },
    'cloud-native': {
      title: 'Cloud-Native Application Development',
      content: `
        <h3>Introduction</h3>
        <p>Cloud-native applications are designed specifically to leverage cloud computing advantages like scalability, resilience, and flexibility.</p>
        
        <h3>Cloud-Native Principles</h3>
        <ul>
          <li>Microservices architecture</li>
          <li>Containerization</li>
          <li>Dynamic orchestration</li>
          <li>Declarative APIs</li>
          <li>DevOps automation</li>
        </ul>
        
        <h3>AWS Serverless Architecture</h3>
        <h4>Key Services:</h4>
        <ul>
          <li><strong>AWS Lambda:</strong> Run code without managing servers</li>
          <li><strong>API Gateway:</strong> Create and manage APIs</li>
          <li><strong>DynamoDB:</strong> Fully managed NoSQL database</li>
          <li><strong>S3:</strong> Object storage for static assets</li>
          <li><strong>CloudFront:</strong> Content delivery network</li>
        </ul>
        
        <h3>Building a Serverless API</h3>
        <h4>Step 1: Create Lambda Function</h4>
        <pre><code>exports.handler = async (event) => {
  const response = {
    statusCode: 200,
    body: JSON.stringify({ message: 'Hello from Lambda!' })
  };
  return response;
};</code></pre>
        
        <h4>Step 2: Configure API Gateway</h4>
        <p>Create REST API endpoints that trigger Lambda functions.</p>
        
        <h4>Step 3: Set Up DynamoDB</h4>
        <p>Create tables for data storage with automatic scaling.</p>
        
        <h3>Infrastructure as Code with Terraform</h3>
        <pre><code>resource "aws_lambda_function" "api" {
  filename      = "lambda.zip"
  function_name = "my-api"
  role          = aws_iam_role.lambda.arn
  handler       = "index.handler"
  runtime       = "nodejs16.x"
}</code></pre>
        
        <h3>Container Services</h3>
        <ul>
          <li><strong>ECS:</strong> Container orchestration service</li>
          <li><strong>EKS:</strong> Managed Kubernetes service</li>
          <li><strong>Fargate:</strong> Serverless container compute</li>
        </ul>
        
        <h3>Scalability Patterns</h3>
        <h4>Auto Scaling</h4>
        <p>Automatically adjust capacity based on demand.</p>
        
        <h4>Load Balancing</h4>
        <p>Distribute traffic across multiple instances.</p>
        
        <h4>Caching</h4>
        <p>Use ElastiCache or CloudFront to reduce latency.</p>
        
        <h3>Monitoring & Observability</h3>
        <ul>
          <li>CloudWatch for logs and metrics</li>
          <li>X-Ray for distributed tracing</li>
          <li>CloudTrail for audit logs</li>
        </ul>
        
        <h3>Cost Optimization</h3>
        <ul>
          <li>Use reserved instances for predictable workloads</li>
          <li>Implement auto-scaling to match demand</li>
          <li>Leverage spot instances for batch processing</li>
          <li>Monitor and optimize resource usage</li>
        </ul>
        
        <h3>Security Best Practices</h3>
        <ul>
          <li>Use IAM roles and policies</li>
          <li>Enable encryption at rest and in transit</li>
          <li>Implement VPC and security groups</li>
          <li>Regular security audits</li>
        </ul>
      `
    },
    'mobile-react-native': {
      title: 'Mobile App Development with React Native',
      content: `
        <h3>Introduction</h3>
        <p>React Native enables you to build native mobile applications using React and JavaScript. Write once, run on both iOS and Android.</p>
        
        <h3>Getting Started</h3>
        <h4>Installation</h4>
        <pre><code>npm install -g react-native-cli
react-native init MyApp
cd MyApp
npm start</code></pre>
        
        <h3>Project Structure</h3>
        <ul>
          <li><code>/android</code> - Android native code</li>
          <li><code>/ios</code> - iOS native code</li>
          <li><code>/src</code> - React Native JavaScript code</li>
          <li><code>App.js</code> - Root component</li>
        </ul>
        
        <h3>Core Components</h3>
        <h4>Basic Components:</h4>
        <ul>
          <li><strong>View:</strong> Container component (like div)</li>
          <li><strong>Text:</strong> Text display component</li>
          <li><strong>Image:</strong> Image display</li>
          <li><strong>ScrollView:</strong> Scrollable container</li>
          <li><strong>FlatList:</strong> Efficient list rendering</li>
        </ul>
        
        <h4>Example Component:</h4>
        <pre><code>import React from 'react';
import { View, Text, StyleSheet } from 'react-native';

const MyComponent = () => {
  return (
    &lt;View style={styles.container}&gt;
      &lt;Text style={styles.title}&gt;Hello React Native!&lt;/Text&gt;
    &lt;/View&gt;
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center'
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold'
  }
});

export default MyComponent;</code></pre>
        
        <h3>Navigation</h3>
        <p>Use React Navigation for routing:</p>
        <pre><code>npm install @react-navigation/native
npm install @react-navigation/stack</code></pre>
        
        <h3>State Management</h3>
        <ul>
          <li>useState and useEffect for local state</li>
          <li>Context API for global state</li>
          <li>Redux or MobX for complex applications</li>
        </ul>
        
        <h3>Native Modules</h3>
        <p>Access device features like camera, GPS, and notifications:</p>
        <ul>
          <li>Camera: react-native-camera</li>
          <li>Maps: react-native-maps</li>
          <li>Push Notifications: react-native-push-notification</li>
        </ul>
        
        <h3>Styling</h3>
        <p>Use StyleSheet API or styled-components for styling mobile apps.</p>
        
        <h3>Testing</h3>
        <ul>
          <li>Jest for unit testing</li>
          <li>React Native Testing Library</li>
          <li>Detox for end-to-end testing</li>
        </ul>
        
        <h3>Performance Optimization</h3>
        <ul>
          <li>Use FlatList for long lists</li>
          <li>Implement memo and useMemo</li>
          <li>Optimize images</li>
          <li>Avoid unnecessary re-renders</li>
        </ul>
        
        <h3>Publishing</h3>
        <h4>iOS App Store:</h4>
        <ul>
          <li>Enroll in Apple Developer Program</li>
          <li>Create app in App Store Connect</li>
          <li>Build and archive with Xcode</li>
          <li>Submit for review</li>
        </ul>
        
        <h4>Google Play Store:</h4>
        <ul>
          <li>Create Google Play Developer account</li>
          <li>Generate signed APK/AAB</li>
          <li>Upload to Play Console</li>
          <li>Complete store listing</li>
        </ul>
      `
    },
    'graphql-api': {
      title: 'GraphQL API Implementation',
      content: `
        <h3>Introduction</h3>
        <p>GraphQL is a query language for APIs that provides a complete and understandable description of the data in your API.</p>
        
        <h3>Why GraphQL?</h3>
        <ul>
          <li>Request exactly the data you need</li>
          <li>Get multiple resources in a single request</li>
          <li>Strong type system</li>
          <li>Self-documenting APIs</li>
        </ul>
        
        <h3>Schema Definition</h3>
        <p>Define your data structure using the GraphQL Schema Definition Language:</p>
        <pre><code>type User {
  id: ID!
  name: String!
  email: String!
  posts: [Post!]!
}

type Post {
  id: ID!
  title: String!
  content: String!
  author: User!
  createdAt: String!
}

type Query {
  users: [User!]!
  user(id: ID!): User
  posts: [Post!]!
}

type Mutation {
  createUser(name: String!, email: String!): User!
  createPost(title: String!, content: String!, authorId: ID!): Post!
}</code></pre>
        
        <h3>Setting Up Apollo Server</h3>
        <pre><code>const { ApolloServer } = require('apollo-server');
const { typeDefs, resolvers } = require('./schema');

const server = new ApolloServer({
  typeDefs,
  resolvers,
  context: ({ req }) => {
    // Add authentication context
    return { user: req.user };
  }
});

server.listen().then(({ url }) => {
  console.log(\`Server ready at \${url}\`);
});</code></pre>
        
        <h3>Resolvers</h3>
        <p>Implement resolver functions to fetch data:</p>
        <pre><code>const resolvers = {
  Query: {
    users: async () => {
      return await User.find();
    },
    user: async (_, { id }) => {
      return await User.findById(id);
    }
  },
  Mutation: {
    createUser: async (_, { name, email }) => {
      const user = new User({ name, email });
      await user.save();
      return user;
    }
  },
  User: {
    posts: async (user) => {
      return await Post.find({ authorId: user.id });
    }
  }
};</code></pre>
        
        <h3>Client Integration</h3>
        <h4>Apollo Client Setup:</h4>
        <pre><code>import { ApolloClient, InMemoryCache, gql } from '@apollo/client';

const client = new ApolloClient({
  uri: 'http://localhost:4000/graphql',
  cache: new InMemoryCache()
});

// Query example
const GET_USERS = gql\`
  query GetUsers {
    users {
      id
      name
      email
    }
  }
\`;

// Mutation example
const CREATE_USER = gql\`
  mutation CreateUser($name: String!, $email: String!) {
    createUser(name: $name, email: $email) {
      id
      name
    }
  }
\`;</code></pre>
        
        <h3>Authentication</h3>
        <p>Implement JWT-based authentication:</p>
        <pre><code>const context = ({ req }) => {
  const token = req.headers.authorization || '';
  const user = validateToken(token);
  return { user };
};</code></pre>
        
        <h3>Real-time Subscriptions</h3>
        <pre><code>type Subscription {
  postAdded: Post!
}

const resolvers = {
  Subscription: {
    postAdded: {
      subscribe: () => pubsub.asyncIterator(['POST_ADDED'])
    }
  }
};</code></pre>
        
        <h3>Performance Optimization</h3>
        <ul>
          <li>Implement DataLoader for batching</li>
          <li>Add query complexity limits</li>
          <li>Use persisted queries</li>
          <li>Implement caching strategies</li>
        </ul>
        
        <h3>Best Practices</h3>
        <ul>
          <li>Design schema thoughtfully</li>
          <li>Use pagination for lists</li>
          <li>Implement proper error handling</li>
          <li>Add rate limiting</li>
          <li>Document your schema</li>
        </ul>
      `
    }
  };

  // ===== SIMULATOR CODE TEMPLATES =====
  const simulatorTemplates = {
    'javascript': {
      name: 'JavaScript Playground',
      defaultCode: `// Welcome to JavaScript Playground!
// Write your JavaScript code here and click Run

function greet(name) {
  return \`Hello, \${name}! Welcome to JavaScript.\`;
}

console.log(greet('Developer'));

// Try some array operations
const numbers = [1, 2, 3, 4, 5];
const doubled = numbers.map(n => n * 2);
console.log('Doubled:', doubled);

// Async example
async function fetchData() {
  return new Promise((resolve) => {
    setTimeout(() => {
      resolve('Data loaded!');
    }, 1000);
  });
}

fetchData().then(data => console.log(data));`
    },
    'python': {
      name: 'Python Lab',
      defaultCode: `# Welcome to Python Lab!
# Note: This is a simulated Python environment

def greet(name):
    return f"Hello, {name}! Welcome to Python."

print(greet('Developer'))

# List comprehension example
numbers = [1, 2, 3, 4, 5]
squared = [n**2 for n in numbers]
print(f"Squared: {squared}")

# Dictionary example
person = {
    'name': 'John',
    'age': 30,
    'city': 'New York'
}
print(f"Person: {person}")

# Simulated output
print("Python Lab is ready for learning!")`
    },
    'react': {
      name: 'React Builder',
      defaultCode: `// Welcome to React Builder!
// Write React component code here

import React, { useState } from 'react';

function Counter() {
  const [count, setCount] = useState(0);
  
  return (
    <div>
      <h1>Counter: {count}</h1>
      <button onClick={() => setCount(count + 1)}>
        Increment
      </button>
      <button onClick={() => setCount(count - 1)}>
        Decrement
      </button>
    </div>
  );
}

function App() {
  return (
    <div className="App">
      <h1>Welcome to React!</h1>
      <Counter />
    </div>
  );
}

export default App;

// Click Run to see the component structure!`
    },
    'nodejs': {
      name: 'Node.js Studio',
      defaultCode: `// Welcome to Node.js Studio!
// Build backend services here

const express = require('express');
const app = express();

// Middleware
app.use(express.json());

// Routes
app.get('/', (req, res) => {
  res.json({ message: 'Welcome to Node.js API!' });
});

app.get('/api/users', (req, res) => {
  const users = [
    { id: 1, name: 'John Doe' },
    { id: 2, name: 'Jane Smith' }
  ];
  res.json(users);
});

app.post('/api/users', (req, res) => {
  const newUser = req.body;
  res.status(201).json(newUser);
});

// Start server
const PORT = 3000;
console.log(\`Server running on port \${PORT}\`);
console.log('API Endpoints:');
console.log('GET  / - Welcome message');
console.log('GET  /api/users - List users');
console.log('POST /api/users - Create user');`
    },
    'java': {
      name: 'Java Workshop',
      defaultCode: `// Welcome to Java Workshop!
// Practice Java programming here

public class Main {
    public static void main(String[] args) {
        System.out.println("Welcome to Java Workshop!");
        
        // Object-Oriented Programming
        Person person = new Person("John", 30);
        System.out.println(person.introduce());
        
        // Array operations
        int[] numbers = {1, 2, 3, 4, 5};
        System.out.println("Sum: " + calculateSum(numbers));
    }
    
    static int calculateSum(int[] arr) {
        int sum = 0;
        for (int num : arr) {
            sum += num;
        }
        return sum;
    }
}

class Person {
    private String name;
    private int age;
    
    public Person(String name, int age) {
        this.name = name;
        this.age = age;
    }
    
    public String introduce() {
        return "Hi, I'm " + name + " and I'm " + age + " years old.";
    }
}

// Click Run to see the output!`
    },
    'typescript': {
      name: 'TypeScript Arena',
      defaultCode: `// Welcome to TypeScript Arena!
// Write type-safe code here

interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'user';
}

class UserManager {
  private users: User[] = [];
  
  addUser(user: User): void {
    this.users.push(user);
    console.log(\`Added user: \${user.name}\`);
  }
  
  getUserById(id: number): User | undefined {
    return this.users.find(u => u.id === id);
  }
  
  getAllUsers(): User[] {
    return this.users;
  }
}

// Usage
const manager = new UserManager();

manager.addUser({
  id: 1,
  name: 'John Doe',
  email: 'john@example.com',
  role: 'admin'
});

manager.addUser({
  id: 2,
  name: 'Jane Smith',
  email: 'jane@example.com',
  role: 'user'
});

console.log('All users:', manager.getAllUsers());

// Type safety in action!
const user = manager.getUserById(1);
if (user) {
  console.log(\`Found: \${user.name} (\${user.role})\`);
}`
    },
    'docker': {
      name: 'Docker Sandbox',
      defaultCode: `# Welcome to Docker Sandbox!
# Learn containerization concepts

# Dockerfile example
FROM node:16-alpine

# Set working directory
WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy application files
COPY . .

# Expose port
EXPOSE 3000

# Start command
CMD ["npm", "start"]

# Docker Compose example
# version: '3.8'
# services:
#   web:
#     build: .
#     ports:
#       - "3000:3000"
#     environment:
#       - NODE_ENV=production
#   
#   db:
#     image: postgres:13
#     environment:
#       - POSTGRES_PASSWORD=secret

# Common Docker commands:
# docker build -t myapp .
# docker run -p 3000:3000 myapp
# docker ps
# docker logs container_id
# docker-compose up

# Click Run to learn more about Docker!`
    },
    'kubernetes': {
      name: 'K8s Simulator',
      defaultCode: `# Welcome to Kubernetes Simulator!
# Learn container orchestration

# Deployment configuration
apiVersion: apps/v1
kind: Deployment
metadata:
  name: web-app
spec:
  replicas: 3
  selector:
    matchLabels:
      app: web
  template:
    metadata:
      labels:
        app: web
    spec:
      containers:
      - name: web
        image: nginx:latest
        ports:
        - containerPort: 80

---
# Service configuration
apiVersion: v1
kind: Service
metadata:
  name: web-service
spec:
  type: LoadBalancer
  selector:
    app: web
  ports:
  - port: 80
    targetPort: 80

# Common kubectl commands:
# kubectl apply -f deployment.yaml
# kubectl get pods
# kubectl get services
# kubectl describe pod pod_name
# kubectl logs pod_name
# kubectl scale deployment web-app --replicas=5

# Key Kubernetes concepts:
# - Pods: Smallest deployable units
# - Deployments: Manage replica sets
# - Services: Network access to pods
# - ConfigMaps: Configuration data
# - Secrets: Sensitive data

# Click Run to explore Kubernetes!`
    },
    'aws': {
      name: 'AWS Cloud Lab',
      defaultCode: `# AWS CLI Simulator — Hands-on Cloud Lab
# Practice AWS commands without incurring real costs

# List all S3 buckets
aws s3 ls

# Create an S3 bucket
aws s3 mb s3://my-demo-bucket --region us-east-1

# Upload a file to S3
aws s3 cp ./app.zip s3://my-demo-bucket/

# List EC2 instances
aws ec2 describe-instances --query 'Reservations[*].Instances[*].[InstanceId,State.Name,InstanceType]' --output table

# Create a Lambda function
aws lambda create-function \\
  --function-name my-function \\
  --runtime nodejs18.x \\
  --role arn:aws:iam::123456789012:role/lambda-role \\
  --handler index.handler \\
  --zip-file fileb://function.zip

# List IAM users
aws iam list-users

# Describe a CloudFormation stack
aws cloudformation describe-stacks --stack-name my-stack

# Click Run to see simulated AWS output`
    },
    'azure': {
      name: 'Azure Sandbox',
      defaultCode: `# Azure CLI Simulator — Cloud Sandbox
# Practice Azure commands in a safe environment

# Login to Azure
az login

# List subscriptions
az account list --output table

# Create a resource group
az group create --name MyResourceGroup --location eastus

# Create a virtual machine
az vm create \\
  --resource-group MyResourceGroup \\
  --name MyVM \\
  --image UbuntuLTS \\
  --admin-username azureuser \\
  --generate-ssh-keys

# List all VMs
az vm list --output table

# Create Azure Container Registry
az acr create --resource-group MyResourceGroup \\
  --name myregistry --sku Basic

# Deploy a web app
az webapp create \\
  --resource-group MyResourceGroup \\
  --plan MyAppServicePlan \\
  --name my-web-app \\
  --runtime "NODE|18-lts"

# Click Run to see simulated Azure output`
    },
    'gcp': {
      name: 'GCP Cloud Shell',
      defaultCode: `# GCP Cloud Shell Simulator
# Practice Google Cloud Platform commands

# Authenticate and set project
gcloud auth login
gcloud config set project my-project-id

# List Compute Engine instances
gcloud compute instances list

# Create a VM instance
gcloud compute instances create my-vm \\
  --zone=us-central1-a \\
  --machine-type=e2-medium \\
  --image-family=debian-11 \\
  --image-project=debian-cloud

# List Cloud Storage buckets
gsutil ls

# Create a GCS bucket
gsutil mb gs://my-gcs-bucket

# List GKE clusters
gcloud container clusters list

# Create a GKE cluster
gcloud container clusters create my-cluster \\
  --zone us-central1-a \\
  --num-nodes 3

# Deploy a Cloud Function
gcloud functions deploy my-function \\
  --runtime nodejs18 \\
  --trigger-http \\
  --allow-unauthenticated

# Click Run to see simulated GCP output`
    },
    'terraform': {
      name: 'Terraform Lab',
      defaultCode: `# Terraform Infrastructure-as-Code Lab
# Write HCL configurations and simulate plan/apply

terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"
    }
  }
}

provider "aws" {
  region = "us-east-1"
}

# S3 Bucket Resource
resource "aws_s3_bucket" "demo_bucket" {
  bucket = "my-terraform-demo-bucket"
  tags = {
    Environment = "Demo"
    ManagedBy   = "Terraform"
  }
}

# EC2 Instance
resource "aws_instance" "web_server" {
  ami           = "ami-0c55b159cbfafe1f0"
  instance_type = "t3.micro"
  tags = {
    Name = "WebServer"
  }
}

# Security Group
resource "aws_security_group" "web_sg" {
  name        = "web-security-group"
  description = "Allow HTTP and HTTPS traffic"
  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

# Click Run to simulate terraform plan & apply`
    },
    'bash': {
      name: 'Bash Terminal',
      defaultCode: `#!/bin/bash
# Bash Terminal Simulator
# Practice shell scripting and Linux commands

# Variables
NAME="CloudFoundry"
echo "Welcome to $NAME!"

# Arithmetic
COUNT=$((10 + 5))
echo "Count: $COUNT"

# Conditional (echoed as documentation)
echo "Check: Count is greater than 10"

# Listing items
echo "Project files:"
for FILE in app.js package.json README.md Dockerfile; do
  echo "  - $FILE"
done

# Function definition and call
deploy() {
  local ENV=$1
  echo "Deploying to $ENV environment..."
  echo "  Building Docker image..."
  echo "  Pushing to registry..."
  echo "  Updating Kubernetes deployment..."
  echo "  Deployment complete!"
}

deploy "production"

# System info output
echo ""
echo "System info:"
echo "  OS: Ubuntu 22.04 LTS"
echo "  Kernel: 5.15.0-1034-aws"
echo "  CPU: 2 vCPU"
echo "  Memory: 4 GB"

# Click Run to execute`
    },
    'cicd': {
      name: 'CI/CD Pipeline Lab',
      defaultCode: `# GitHub Actions CI/CD Pipeline
# Simulate your deployment pipeline

name: Deploy to Production
on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

env:
  NODE_VERSION: '18'
  AWS_REGION: 'us-east-1'

jobs:
  test:
    name: Run Tests
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: \${{ env.NODE_VERSION }}
      - name: Install dependencies
        run: npm ci
      - name: Run unit tests
        run: npm test
      - name: Run linter
        run: npm run lint

  build:
    name: Build and Push Docker Image
    needs: test
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Build Docker image
        run: docker build -t myapp:\${{ github.sha }} .
      - name: Push to ECR
        run: |
          aws ecr get-login-password | docker login --username AWS --password-stdin \$ECR_URL
          docker push \$ECR_URL/myapp:\${{ github.sha }}

  deploy:
    name: Deploy to Kubernetes
    needs: build
    runs-on: ubuntu-latest
    steps:
      - name: Update K8s deployment
        run: |
          kubectl set image deployment/myapp myapp=\$ECR_URL/myapp:\${{ github.sha }}
          kubectl rollout status deployment/myapp

# Click Run to simulate the pipeline`
    }
  };

  // ===== MODAL FUNCTIONS =====
  const modalOverlay = document.getElementById('modalOverlay');
  const modalContent = document.getElementById('modalContent');

  function closeModal() {
    modalOverlay.classList.remove('active');
    modalContent.innerHTML = '';
  }

  modalOverlay?.addEventListener('click', (e) => {
    if (e.target === modalOverlay) {
      closeModal();
    }
  });

  // ===== QUIZ FUNCTIONALITY =====
  let currentQuiz = null;
  let currentQuizKey = null;
  let currentQuizLevel = null;
  let currentQuestionIndex = 0;
  let userAnswers = [];

  function startQuiz(quizKey) {
    currentQuiz = quizData[quizKey];
    if (!currentQuiz) return;

    currentQuizKey = quizKey;
    currentQuizLevel = null;

    const levelKeys = Object.keys(currentQuiz.levels);
    modalContent.innerHTML = `
      <div class="modal-header">
        <h2 class="modal-title">${currentQuiz.title}</h2>
        <button class="modal-close" onclick="closeModal()">
          <iconify-icon icon="lucide:x"></iconify-icon>
        </button>
      </div>
      <div class="modal-body">
        <div class="quiz-level-selector">
          <h3>Choose Your Difficulty Level</h3>
          <p>Select the level that best matches your experience</p>
          <div class="level-options">
            ${levelKeys.map(key => {
              const lvl = currentQuiz.levels[key];
              return `
                <div class="level-option" onclick="selectQuizLevel('${key}')">
                  <div class="level-option-header">
                    <span class="level-badge ${key}">${lvl.label}</span>
                    <span class="level-option-name">${lvl.label} — ${lvl.questions.length} Questions</span>
                  </div>
                  <div class="level-option-desc">${lvl.description}</div>
                </div>`;
            }).join('')}
          </div>
        </div>
      </div>
    `;

    modalOverlay.classList.add('active');
  }

  function selectQuizLevel(level) {
    if (!currentQuiz || !currentQuiz.levels[level]) return;
    currentQuizLevel = level;
    currentQuestionIndex = 0;
    userAnswers = new Array(currentQuiz.levels[level].questions.length).fill(null);
    showQuestion();
  }

  function showQuestion() {
    const levelData = currentQuiz.levels[currentQuizLevel];
    const question = levelData.questions[currentQuestionIndex];
    const totalQuestions = levelData.questions.length;

    modalContent.innerHTML = `
      <div class="modal-header">
        <h2 class="modal-title">${currentQuiz.title}</h2>
        <button class="modal-close" onclick="closeModal()">
          <iconify-icon icon="lucide:x"></iconify-icon>
        </button>
      </div>
      <div class="modal-body">
        <div class="quiz-progress">
          <span>Question ${currentQuestionIndex + 1} of ${totalQuestions}</span>
          <span style="display:flex;align-items:center;gap:8px;">
            <span class="quiz-level-pill ${currentQuizLevel}">${levelData.label}</span>
            ${Math.round(((currentQuestionIndex + 1) / totalQuestions) * 100)}% Complete
          </span>
        </div>
        <div class="quiz-question">
          <div class="quiz-question-text">${question.question}</div>
          <div class="quiz-options">
            ${question.options.map((option, index) => `
              <div class="quiz-option ${userAnswers[currentQuestionIndex] === index ? 'selected' : ''}"
                   onclick="selectAnswer(${index})">
                ${option}
              </div>
            `).join('')}
          </div>
        </div>
        <div class="quiz-navigation">
          <button class="simulator-btn-secondary" onclick="previousQuestion()"
                  ${currentQuestionIndex === 0 ? 'disabled' : ''}>
            Previous
          </button>
          <button class="simulator-btn-primary" onclick="${currentQuestionIndex === totalQuestions - 1 ? 'finishQuiz()' : 'nextQuestion()'}">
            ${currentQuestionIndex === totalQuestions - 1 ? 'Finish Quiz' : 'Next'}
          </button>
        </div>
      </div>
    `;
  }

  function selectAnswer(answerIndex) {
    userAnswers[currentQuestionIndex] = answerIndex;
    showQuestion();
  }

  function nextQuestion() {
    const total = currentQuiz.levels[currentQuizLevel].questions.length;
    if (currentQuestionIndex < total - 1) {
      currentQuestionIndex++;
      showQuestion();
    }
  }

  function previousQuestion() {
    if (currentQuestionIndex > 0) {
      currentQuestionIndex--;
      showQuestion();
    }
  }

  function finishQuiz() {
    const levelData = currentQuiz.levels[currentQuizLevel];
    let correct = 0;
    levelData.questions.forEach((question, index) => {
      if (userAnswers[index] === question.correct) correct++;
    });

    const total = levelData.questions.length;
    const percentage = Math.round((correct / total) * 100);

    modalContent.innerHTML = `
      <div class="modal-header">
        <h2 class="modal-title">Quiz Results</h2>
        <button class="modal-close" onclick="closeModal()">
          <iconify-icon icon="lucide:x"></iconify-icon>
        </button>
      </div>
      <div class="modal-body">
        <div class="quiz-results">
          <span class="quiz-level-pill ${currentQuizLevel}" style="font-size:0.85rem;padding:4px 12px;">
            ${levelData.label} Level
          </span>
          <div class="quiz-score">${percentage}%</div>
          <div class="quiz-result-details">
            <div class="quiz-result-stat">
              <div class="quiz-result-stat-value">${correct}</div>
              <div class="quiz-result-stat-label">Correct</div>
            </div>
            <div class="quiz-result-stat">
              <div class="quiz-result-stat-value">${total - correct}</div>
              <div class="quiz-result-stat-label">Incorrect</div>
            </div>
            <div class="quiz-result-stat">
              <div class="quiz-result-stat-value">${total}</div>
              <div class="quiz-result-stat-label">Total</div>
            </div>
          </div>
          <p style="margin-top:20px;color:var(--text-muted);">
            ${percentage >= 80 ? 'Excellent work! You have a strong grasp of this level.' :
              percentage >= 60 ? 'Good job! Review the topics you missed to improve.' :
              'Keep learning! Study the material and try again.'}
          </p>
          <div style="margin-top:30px;display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <button class="simulator-btn-secondary" onclick="startQuiz('${currentQuizKey}')">
              Change Level
            </button>
            <button class="simulator-btn-primary" onclick="selectQuizLevel('${currentQuizLevel}')">
              Retake Quiz
            </button>
          </div>
        </div>
      </div>
    `;
  }

  // Make functions globally accessible
  window.selectAnswer = selectAnswer;
  window.nextQuestion = nextQuestion;
  window.previousQuestion = previousQuestion;
  window.finishQuiz = finishQuiz;
  window.selectQuizLevel = selectQuizLevel;

  // ===== GUIDE FUNCTIONALITY =====
  function showGuide(guideKey) {
    const guide = guideContent[guideKey];
    if (!guide) return;
    
    modalContent.innerHTML = `
      <div class="modal-header">
        <h2 class="modal-title">${guide.title}</h2>
        <button class="modal-close" onclick="closeModal()">
          <iconify-icon icon="lucide:x"></iconify-icon>
        </button>
      </div>
      <div class="modal-body guide-modal-content">
        ${guide.content}
      </div>
    `;
    
    modalOverlay.classList.add('active');
  }

  // ===== SIMULATOR FUNCTIONALITY =====
  let _currentSimKey = null;

  function launchSimulator(simKey) {
    const simulator = simulatorTemplates[simKey];
    if (!simulator) return;
    _currentSimKey = simKey;

    modalContent.innerHTML = `
      <div class="modal-header">
        <h2 class="modal-title">${simulator.name}</h2>
        <button class="modal-close" onclick="closeModal()">
          <iconify-icon icon="lucide:x"></iconify-icon>
        </button>
      </div>
      <div class="modal-body">
        <div class="simulator-interface">
          <div class="simulator-editor">
            <div class="simulator-toolbar">
              <span style="font-weight:600;">Code Editor</span>
              <div class="simulator-actions">
                <button class="simulator-btn-secondary" onclick="resetSimulator('${simKey}')">Reset</button>
                <button class="simulator-btn-primary" id="runBtn" onclick="runSimulator()">
                  <iconify-icon icon="lucide:play"></iconify-icon> Run Code
                </button>
              </div>
            </div>
            <textarea class="simulator-code" id="simulatorCode">${simulator.defaultCode}</textarea>
          </div>
          <div>
            <div style="font-weight:600;margin-bottom:8px;padding-left:4px;">Output:</div>
            <div class="simulator-output" id="simulatorOutput">Click "Run Code" to execute your code...</div>
          </div>
        </div>
      </div>
    `;

    modalOverlay.classList.add('active');
  }

  function _setOutput(text) {
    const el = document.getElementById('simulatorOutput');
    if (el) el.textContent = text;
  }

  function _runJavaScript(code) {
    const logs = [];
    const _console = {
      log:   (...a) => logs.push(a.map(_fmt).join(' ')),
      warn:  (...a) => logs.push('⚠ ' + a.map(_fmt).join(' ')),
      error: (...a) => logs.push('✖ ' + a.map(_fmt).join(' ')),
      info:  (...a) => logs.push('ℹ ' + a.map(_fmt).join(' ')),
    };
    try {
      const fn = new Function('console', code);
      fn(_console);
      return logs.length ? logs.join('\n') + '\n\n✓ Execution completed' : '(No output — add console.log() statements)\n\n✓ Execution completed';
    } catch (e) {
      return '✖ Runtime error: ' + e.message + (logs.length ? '\n\n--- Output before error ---\n' + logs.join('\n') : '');
    }
  }

  function _fmt(v) {
    if (v === null) return 'null';
    if (v === undefined) return 'undefined';
    if (typeof v === 'object') {
      try { return JSON.stringify(v, null, 2); } catch(_) { return String(v); }
    }
    return String(v);
  }

  function _loadScript(src) {
    return new Promise((resolve, reject) => {
      if (document.querySelector('script[src="' + src + '"]')) { resolve(); return; }
      const s = document.createElement('script');
      s.src = src;
      s.onload = resolve;
      s.onerror = () => reject(new Error('Failed to load: ' + src));
      document.head.appendChild(s);
    });
  }

  async function _runPython(code) {
    _setOutput('> Loading Python interpreter...');
    try {
      await _loadScript('https://cdn.jsdelivr.net/npm/skulpt@1.2.0/dist/skulpt.min.js');
      await _loadScript('https://cdn.jsdelivr.net/npm/skulpt@1.2.0/dist/skulpt-stdlib.js');
    } catch (_) {
      _setOutput('✖ Could not load Python interpreter. Check your internet connection.');
      return;
    }
    let output = '';
    Sk.configure({
      output: (text) => { output += text; },
      read: (x) => {
        if (Sk.builtinFiles && Sk.builtinFiles.files[x] !== undefined) return Sk.builtinFiles.files[x];
        throw 'File not found: \'' + x + '\'';
      }
    });
    _setOutput('> Running Python code...');
    try {
      await Sk.misceval.asyncToPromise(() => Sk.importMainWithBody('<stdin>', false, code, true));
      _setOutput(output.length ? output + '\n✓ Execution completed' : '(No output — add print() statements)\n\n✓ Execution completed');
    } catch (e) {
      _setOutput('✖ ' + (e.toString ? e.toString() : String(e)) + (output ? '\n\n--- Output before error ---\n' + output : ''));
    }
  }

  async function _runTypeScript(code) {
    _setOutput('> Loading TypeScript compiler...');
    try {
      await _loadScript('https://cdnjs.cloudflare.com/ajax/libs/typescript/5.3.3/typescript.min.js');
    } catch (_) {
      _setOutput('✖ Could not load TypeScript compiler. Check your internet connection.');
      return;
    }
    let jsCode;
    try {
      const result = ts.transpileModule(code, {
        compilerOptions: { target: ts.ScriptTarget.ES2017, module: ts.ModuleKind.None }
      });
      jsCode = result.outputText;
    } catch (e) {
      _setOutput('✖ TypeScript compilation error: ' + e.message);
      return;
    }
    _setOutput('> Running transpiled JavaScript...\n\n' + _runJavaScript(jsCode));
  }

  async function _runReact(code) {
    _setOutput('> Loading React + Babel transpiler...');
    try {
      await _loadScript('https://unpkg.com/react@18/umd/react.development.js');
      await _loadScript('https://unpkg.com/react-dom@18/umd/react-dom.development.js');
      await _loadScript('https://unpkg.com/@babel/standalone/babel.min.js');
    } catch (_) {
      _setOutput('✖ Could not load React/Babel. Check your internet connection.');
      return;
    }
    try {
      const transformed = Babel.transform(code, { presets: ['react'] }).code;
      const frame = document.createElement('iframe');
      frame.style.cssText = 'width:100%;height:260px;border:none;background:#fff;border-radius:6px;margin-top:8px;';
      const outputEl = document.getElementById('simulatorOutput');
      outputEl.textContent = '';
      outputEl.appendChild(frame);
      const doc = frame.contentDocument;
      doc.open();
      doc.write(`<!DOCTYPE html><html><head>
        <script src="https://unpkg.com/react@18/umd/react.development.js"><\/script>
        <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"><\/script>
        <style>body{font-family:sans-serif;padding:16px;margin:0;background:#fff;color:#111;}</style>
      </head><body><div id="root"></div><script>
        try { ${transformed}
          const root = ReactDOM.createRoot(document.getElementById('root'));
          const AppComp = typeof App !== 'undefined' ? App : (typeof Counter !== 'undefined' ? Counter : null);
          if (AppComp) root.render(React.createElement(AppComp));
          else document.getElementById('root').innerHTML = '<p style="color:red">No App component found. Define a component named App.</p>';
        } catch(e) { document.getElementById('root').innerHTML = '<p style="color:red">Error: ' + e.message + '</p>'; }
      <\/script></body></html>`);
      doc.close();
    } catch (e) {
      _setOutput('✖ Transpile/render error: ' + e.message);
    }
  }

  function _runJava(code) {
    const lines = [];
    const printRe = /System\.out\.println\s*\(\s*(.*?)\s*\)\s*;/g;
    let m;
    const evalJavaExpr = (expr) => {
      const strMatch = expr.match(/^"(.*)"$/);
      if (strMatch) return strMatch[1];
      try { return Function('"use strict"; return ' + expr.replace(/\bthis\b/g,'undefined'))(); } catch(_) { return expr; }
    };
    while ((m = printRe.exec(code)) !== null) lines.push(evalJavaExpr(m[1]));
    const classes = [...code.matchAll(/\bclass\s+(\w+)/g)].map(x => x[1]);
    const methods = [...code.matchAll(/(?:public|private|protected|static)[\w\s<>[\]]+\s+(\w+)\s*\(/g)].map(x => x[1]).filter(n => n !== 'main');
    let result = '';
    if (classes.length) result += '> Compiling: ' + classes.join(', ') + '\n';
    if (methods.length) result += '> Methods: ' + methods.join(', ') + '\n';
    if (lines.length || result) result += '\n--- Output ---\n';
    result += lines.join('\n');
    result += lines.length ? '\n\n✓ Compilation and execution successful' : '(No System.out.println() calls found)\n\n✓ Compilation successful';
    return result;
  }

  function _runDocker(code) {
    const instructions = [];
    const lines = code.split('\n');
    const validInstructions = ['FROM','RUN','CMD','LABEL','EXPOSE','ENV','ADD','COPY','ENTRYPOINT','VOLUME','USER','WORKDIR','ARG','ONBUILD','STOPSIGNAL','HEALTHCHECK','SHELL'];
    let step = 0;
    for (const line of lines) {
      const trimmed = line.trim();
      if (!trimmed || trimmed.startsWith('#')) continue;
      const keyword = trimmed.split(/\s+/)[0].toUpperCase();
      if (validInstructions.includes(keyword)) {
        step++;
        const rest = trimmed.substring(keyword.length).trim();
        if (keyword === 'FROM') instructions.push(`Step ${step}: Pulling base image ${rest}`);
        else if (keyword === 'WORKDIR') instructions.push(`Step ${step}: Setting working directory to ${rest}`);
        else if (keyword === 'COPY') instructions.push(`Step ${step}: Copying files: ${rest}`);
        else if (keyword === 'RUN') instructions.push(`Step ${step}: Executing: ${rest}`);
        else if (keyword === 'EXPOSE') instructions.push(`Step ${step}: Exposing port ${rest}`);
        else if (keyword === 'CMD') instructions.push(`Step ${step}: Setting default command: ${rest}`);
        else if (keyword === 'ENV') instructions.push(`Step ${step}: Setting env variable: ${rest}`);
        else instructions.push(`Step ${step}: ${keyword} ${rest}`);
      }
    }
    if (!instructions.length) return '(No valid Dockerfile instructions found)';
    return '> docker build -t myapp .\n\n' + instructions.join('\n') + '\n\nSuccessfully built myapp\nSuccessfully tagged myapp:latest\n\n✓ Build simulation complete';
  }

  function _runKubernetes(code) {
    const resources = [];
    let current = null;
    for (const line of code.split('\n')) {
      const trimmed = line.trim();
      if (trimmed === '---') { if (current) resources.push(current); current = null; continue; }
      if (!current) current = {};
      const kindMatch = trimmed.match(/^kind:\s*(.+)/);
      const nameMatch = trimmed.match(/^name:\s*(.+)/);
      const nsMatch = trimmed.match(/^namespace:\s*(.+)/);
      const replicaMatch = trimmed.match(/^replicas:\s*(\d+)/);
      if (kindMatch) current.kind = kindMatch[1].trim();
      if (nameMatch && !current.name) current.name = nameMatch[1].trim();
      if (nsMatch) current.namespace = nsMatch[1].trim();
      if (replicaMatch) current.replicas = replicaMatch[1].trim();
    }
    if (current && current.kind) resources.push(current);
    if (!resources.length) return '(No valid Kubernetes resources found in YAML)';
    const lines = ['> kubectl apply -f manifest.yaml', ''];
    resources.forEach(r => {
      const ns = r.namespace ? ' in namespace ' + r.namespace : '';
      const extra = r.replicas ? ` (${r.replicas} replicas)` : '';
      lines.push(`${r.kind.toLowerCase()}/${r.name || 'unnamed'} created${ns}${extra}`);
    });
    lines.push('');
    resources.forEach(r => {
      if (r.kind === 'Deployment') lines.push(`✓ Deployment rolling out: ${r.name}${r.replicas ? ' (' + r.replicas + ' pods)' : ''}`);
      if (r.kind === 'Service') lines.push(`✓ Service endpoints registered: ${r.name}`);
      if (r.kind === 'ConfigMap') lines.push(`✓ ConfigMap mounted: ${r.name}`);
    });
    lines.push('\n✓ Resources applied successfully');
    return lines.join('\n');
  }

  function _runAWS(code) {
    const lines = code.split('\n').map(l => l.trim()).filter(l => l && !l.startsWith('#'));
    const output = [];
    output.push('AWS CLI Simulator — Sandbox Environment');
    output.push('Account: 123456789012 | Region: us-east-1');
    output.push('');
    let commandFound = false;
    for (const raw of lines) {
      const cmd = raw.replace(/\s*\\\s*$/, '').trim();
      if (!cmd.startsWith('aws ')) continue;
      commandFound = true;
      output.push('$ ' + cmd);
      if (/aws s3 ls/.test(cmd)) {
        output.push('2024-01-15 10:23:01 my-app-bucket');
        output.push('2024-02-20 14:11:45 my-backup-bucket');
        output.push('2024-03-01 09:05:30 my-logs-bucket');
      } else if (/aws s3 mb/.test(cmd)) {
        const m = cmd.match(/s3:\/\/([\w-]+)/);
        output.push('make_bucket: ' + (m ? m[1] : 'new-bucket'));
      } else if (/aws s3 cp/.test(cmd)) {
        output.push('upload: ./app.zip to s3://my-demo-bucket/app.zip');
      } else if (/aws ec2 describe-instances/.test(cmd)) {
        output.push('--------------------------------------------');
        output.push('|         DescribeInstances                |');
        output.push('+-----------+----------+---------------+');
        output.push('| i-0abc123 | running  | t3.micro      |');
        output.push('| i-0def456 | stopped  | t3.small      |');
        output.push('+-----------+----------+---------------+');
      } else if (/aws lambda create-function/.test(cmd)) {
        output.push(JSON.stringify({ FunctionName: 'my-function', Runtime: 'nodejs18.x', State: 'Active', FunctionArn: 'arn:aws:lambda:us-east-1:123456789012:function:my-function' }, null, 2));
      } else if (/aws iam list-users/.test(cmd)) {
        output.push('{ "Users": [ { "UserName": "admin" }, { "UserName": "developer" } ] }');
      } else if (/aws cloudformation describe-stacks/.test(cmd)) {
        output.push('{ "Stacks": [ { "StackName": "my-stack", "StackStatus": "CREATE_COMPLETE" } ] }');
      } else {
        output.push('{ "ResponseMetadata": { "RequestId": "abc-123", "HTTPStatusCode": 200 } }');
      }
      output.push('');
    }
    if (!commandFound) return '(No AWS CLI commands found — commands must start with "aws ")\n\n✓ Sandbox ready';
    output.push('✓ All commands executed in simulated AWS environment');
    return output.join('\n');
  }

  function _runAzure(code) {
    const lines = code.split('\n').map(l => l.trim()).filter(l => l && !l.startsWith('#'));
    const output = [];
    output.push('Azure CLI Simulator — Sandbox Environment');
    output.push('Subscription: Demo Subscription (00000000-0000-0000-0000-000000000000)');
    output.push('');
    let commandFound = false;
    for (const raw of lines) {
      const cmd = raw.replace(/\s*\\\s*$/, '').trim();
      if (!cmd.startsWith('az ')) continue;
      commandFound = true;
      output.push('$ ' + cmd);
      if (/az login/.test(cmd)) {
        output.push('[{ "cloudName": "AzureCloud", "name": "Demo Subscription", "state": "Enabled", "isDefault": true }]');
      } else if (/az account list/.test(cmd)) {
        output.push('Name                  State    IsDefault');
        output.push('--------------------  -------  ---------');
        output.push('Demo Subscription     Enabled  True');
      } else if (/az group create/.test(cmd)) {
        const rg = cmd.match(/--name\s+([\w-]+)/)?.[1] || 'MyResourceGroup';
        output.push(`{ "name": "${rg}", "location": "eastus", "properties": { "provisioningState": "Succeeded" } }`);
      } else if (/az vm create/.test(cmd)) {
        output.push('{ "fqdns": "", "location": "eastus", "powerState": "VM running", "privateIpAddress": "10.0.0.4" }');
      } else if (/az vm list/.test(cmd)) {
        output.push('Name   ResourceGroup      Location  PowerState');
        output.push('-----  -----------------  --------  ----------');
        output.push('MyVM   MyResourceGroup    eastus    VM running');
      } else if (/az acr create/.test(cmd)) {
        output.push('{ "loginServer": "myregistry.azurecr.io", "name": "myregistry", "provisioningState": "Succeeded" }');
      } else if (/az webapp create/.test(cmd)) {
        output.push('{ "defaultHostName": "my-web-app.azurewebsites.net", "name": "my-web-app", "state": "Running" }');
      } else {
        output.push('{ "status": "Succeeded" }');
      }
      output.push('');
    }
    if (!commandFound) return '(No Azure CLI commands found — commands must start with "az ")\n\n✓ Sandbox ready';
    output.push('✓ All commands executed in simulated Azure environment');
    return output.join('\n');
  }

  function _runGCP(code) {
    const lines = code.split('\n').map(l => l.trim()).filter(l => l && !l.startsWith('#'));
    const output = [];
    output.push('GCP Cloud Shell Simulator — Sandbox Environment');
    output.push('Project: my-project-id | Region: us-central1');
    output.push('');
    let commandFound = false;
    for (const raw of lines) {
      const cmd = raw.replace(/\s*\\\s*$/, '').trim();
      if (!cmd.startsWith('gcloud') && !cmd.startsWith('gsutil') && !cmd.startsWith('kubectl')) continue;
      commandFound = true;
      output.push('$ ' + cmd);
      if (/gcloud auth login/.test(cmd)) {
        output.push('You are now logged in as [user@example.com].');
        output.push('Your current project is [my-project-id].');
      } else if (/gcloud config set project/.test(cmd)) {
        output.push('Updated property [core/project].');
      } else if (/gcloud compute instances list/.test(cmd)) {
        output.push('NAME    ZONE           MACHINE_TYPE  STATUS');
        output.push('my-vm   us-central1-a  e2-medium     RUNNING');
        output.push('api-vm  us-central1-b  e2-small      TERMINATED');
      } else if (/gcloud compute instances create/.test(cmd)) {
        output.push('NAME   ZONE           MACHINE_TYPE  PREEMPTIBLE  INTERNAL_IP  EXTERNAL_IP   STATUS');
        output.push('my-vm  us-central1-a  e2-medium                  10.128.0.2   34.67.100.10  RUNNING');
      } else if (/gsutil ls/.test(cmd)) {
        output.push('gs://my-app-bucket/');
        output.push('gs://my-backup-bucket/');
      } else if (/gsutil mb/.test(cmd)) {
        const m = cmd.match(/gs:\/\/([\w-]+)/);
        output.push('Creating ' + (m ? 'gs://' + m[1] + '/' : 'bucket') + '...');
      } else if (/gcloud container clusters list/.test(cmd)) {
        output.push('NAME        LOCATION       MASTER_VERSION  NUM_NODES  STATUS');
        output.push('my-cluster  us-central1-a  1.27.3-gke      3          RUNNING');
      } else if (/gcloud container clusters create/.test(cmd)) {
        output.push('Creating cluster my-cluster in us-central1-a...');
        output.push('kubeconfig entry generated for my-cluster.');
        output.push('NAME        LOCATION       NUM_NODES  STATUS');
        output.push('my-cluster  us-central1-a  3          RUNNING');
      } else if (/gcloud functions deploy/.test(cmd)) {
        output.push('Deploying function...');
        output.push('httpsTrigger: { url: "https://us-central1-my-project.cloudfunctions.net/my-function" }');
        output.push('status: ACTIVE');
      } else {
        output.push('OK');
      }
      output.push('');
    }
    if (!commandFound) return '(No gcloud/gsutil commands found)\n\n✓ Cloud Shell ready';
    output.push('✓ All commands executed in simulated GCP environment');
    return output.join('\n');
  }

  function _runTerraform(code) {
    const resources = [];
    const providers = [];
    const providerRe = /provider\s+"([\w-]+)"/g;
    const resourceRe = /resource\s+"([\w-]+)"\s+"([\w-]+)"/g;
    let m;
    while ((m = providerRe.exec(code)) !== null) providers.push(m[1]);
    while ((m = resourceRe.exec(code)) !== null) resources.push({ type: m[1], name: m[2] });
    const output = [];
    output.push('> terraform init');
    output.push('');
    output.push('Initializing the backend...');
    output.push('Initializing provider plugins...');
    if (providers.length) {
      providers.forEach(p => output.push('  - Finding hashicorp/' + p + ' versions...'));
      providers.forEach(p => output.push('  - Installing hashicorp/' + p + '...'));
    }
    output.push('');
    output.push('Terraform has been successfully initialized!');
    output.push('');
    output.push('> terraform plan');
    output.push('');
    if (!resources.length) {
      output.push('No changes. Your infrastructure matches the configuration.');
      return output.join('\n') + '\n\n✓ Terraform simulation complete';
    }
    output.push('Terraform will perform the following actions:');
    output.push('');
    resources.forEach(r => {
      output.push('  # ' + r.type + '.' + r.name + ' will be created');
      output.push('  + resource "' + r.type + '" "' + r.name + '" {');
      output.push('      + id  = (known after apply)');
      output.push('      + arn = (known after apply)');
      output.push('    }');
      output.push('');
    });
    output.push('Plan: ' + resources.length + ' to add, 0 to change, 0 to destroy.');
    output.push('');
    output.push('> terraform apply -auto-approve');
    output.push('');
    resources.forEach((r, i) => {
      const suffix = Math.random().toString(36).substring(2, 8);
      output.push(r.type + '.' + r.name + ': Creating...');
      output.push(r.type + '.' + r.name + ': Creation complete after ' + (i + 1) + 's [id=' + r.type.split('_').pop() + '-' + suffix + ']');
    });
    output.push('');
    output.push('Apply complete! Resources: ' + resources.length + ' added, 0 changed, 0 destroyed.');
    output.push('\n✓ Terraform simulation complete');
    return output.join('\n');
  }

  function _runBash(code) {
    const lines = code.split('\n');
    const output = [];
    const vars = {};
    const functions = {};
    let currentFn = null;
    let fnLines = [];
    for (const line of lines) {
      const trimmed = line.trim();
      if (/^\w+\s*\(\)\s*\{/.test(trimmed) || /^function\s+\w+/.test(trimmed)) {
        currentFn = trimmed.match(/^(?:function\s+)?(\w+)/)?.[1] || null;
        fnLines = [];
      } else if (currentFn && trimmed === '}') {
        functions[currentFn] = [...fnLines];
        currentFn = null;
      } else if (currentFn) {
        fnLines.push(trimmed);
      }
    }
    for (const line of lines) {
      const trimmed = line.trim();
      if (!trimmed || trimmed.startsWith('#') || /^\w+\s*\(\)\s*\{/.test(trimmed) || /^function\s+\w+/.test(trimmed) || trimmed === '}') continue;
      if (/^(if|fi|for|do|done|then|else|\[\[|shebang)/.test(trimmed) || trimmed === 'do' || trimmed === 'done') continue;
      const varMatch = trimmed.match(/^([A-Z_][A-Z0-9_]*)=(.+)$/);
      if (varMatch) {
        let val = varMatch[2].replace(/^["']|["']$/g, '');
        val = val.replace(/\$\(\(([^)]+)\)\)/g, (_, expr) => {
          try { return String(Function('"use strict";return (' + expr + ')')()) } catch(_) { return '0'; }
        });
        vars[varMatch[1]] = val;
        continue;
      }
      if (/^echo\s/.test(trimmed)) {
        let msg = trimmed.replace(/^echo\s+/, '').replace(/^["']|["']$/g, '');
        msg = msg.replace(/\$\{?(\w+)\}?/g, (_, n) => vars[n] !== undefined ? vars[n] : '$' + n);
        output.push(msg);
        continue;
      }
      const fnCallMatch = trimmed.match(/^(\w+)\s*(?:"([^"]*)"|\S+)?/);
      if (fnCallMatch && functions[fnCallMatch[1]]) {
        const arg = fnCallMatch[2] || fnCallMatch[0].split(/\s+/)[1]?.replace(/['"]/g, '') || '';
        for (const fl of functions[fnCallMatch[1]]) {
          if (!fl || fl.startsWith('#')) continue;
          if (/^local\s/.test(fl)) {
            const parts = fl.replace(/^local\s+/, '').split('=');
            if (parts[1]) vars[parts[0]] = parts[1].replace(/['"]/g, '').replace(/\$1/g, arg);
            continue;
          }
          if (/^echo\s/.test(fl)) {
            let msg = fl.replace(/^echo\s+/, '').replace(/^["']|["']$/g, '');
            msg = msg.replace(/\$1/g, arg).replace(/\$\{?(\w+)\}?/g, (_, n) => vars[n] !== undefined ? vars[n] : '$' + n);
            output.push(msg);
          }
        }
      }
    }
    if (!output.length) return '(No output — add echo statements to see results)\n\n✓ Shell ready';
    return output.join('\n') + '\n\n✓ Script execution complete';
  }

  function _runCICD(code) {
    const jobRe = /^\s{2}([\w-]+):/gm;
    const jobs = [];
    let m;
    while ((m = jobRe.exec(code)) !== null) {
      if (!['on', 'env', 'name', 'push', 'pull_request'].includes(m[1])) jobs.push(m[1]);
    }
    const wfName = code.match(/^name:\s*(.+)/m)?.[1]?.trim() || 'CI/CD Pipeline';
    const branch = code.match(/branches:\s*\[\s*([\w,\s/]+)\]/)?.[1]?.split(',')[0]?.trim() || 'main';
    const runId = Math.floor(Math.random() * 9000) + 1000;
    const output = [];
    output.push('\uD83D\uDE80 Running: ' + wfName);
    output.push('   Trigger: push to ' + branch);
    output.push('   Run ID: #' + runId);
    output.push('');
    const icons = { test: '\uD83E\uDDEA', build: '\uD83D\uDD28', deploy: '\uD83D\uDE80' };
    const durations = { test: '1m 24s', build: '2m 07s', deploy: '45s' };
    jobs.forEach((job, i) => {
      const icon = icons[job] || '\u2699\uFE0F';
      const duration = durations[job] || (Math.floor(Math.random() * 60) + 20) + 's';
      output.push(icon + ' Job: ' + job);
      output.push('   Runner: ubuntu-latest');
      const jobIdx = code.indexOf('  ' + job + ':');
      const nextJobIdx = jobs[i + 1] ? code.indexOf('  ' + jobs[i + 1] + ':', jobIdx + 1) : code.length;
      const jobBody = code.substring(jobIdx, nextJobIdx);
      const stepRe = /- name:\s*(.+)/g;
      const steps = [];
      let sm;
      while ((sm = stepRe.exec(jobBody)) !== null) steps.push(sm[1].trim());
      if (steps.length) {
        steps.forEach(s => output.push('   \u2713 ' + s));
      } else {
        output.push('   \u2713 Steps completed');
      }
      output.push('   \u23F1 Duration: ' + duration);
      output.push('   \u2705 ' + job + ' \u2014 Passed');
      output.push('');
    });
    output.push('\u2501'.repeat(34));
    output.push('\u2705 Pipeline completed successfully');
    output.push('   Total jobs: ' + jobs.length + ' | All passed');
    output.push('\n\u2713 CI/CD simulation complete');
    return output.join('\n');
  }

  async function runSimulator() {
    const code = document.getElementById('simulatorCode')?.value || '';
    const runBtn = document.getElementById('runBtn');
    if (runBtn) { runBtn.disabled = true; runBtn.textContent = 'Running...'; }

    _setOutput('> Running code...');

    const key = _currentSimKey;
    let result;

    if (key === 'javascript' || key === 'nodejs') {
      result = _runJavaScript(code);
      _setOutput(result);
    } else if (key === 'python') {
      await _runPython(code);
    } else if (key === 'typescript') {
      await _runTypeScript(code);
    } else if (key === 'react') {
      await _runReact(code);
    } else if (key === 'java') {
      result = _runJava(code);
      _setOutput(result);
    } else if (key === 'docker') {
      result = _runDocker(code);
      _setOutput(result);
    } else if (key === 'kubernetes') {
      result = _runKubernetes(code);
      _setOutput(result);
    } else if (key === 'aws') {
      result = _runAWS(code);
      _setOutput(result);
    } else if (key === 'azure') {
      result = _runAzure(code);
      _setOutput(result);
    } else if (key === 'gcp') {
      result = _runGCP(code);
      _setOutput(result);
    } else if (key === 'terraform') {
      result = _runTerraform(code);
      _setOutput(result);
    } else if (key === 'bash') {
      result = _runBash(code);
      _setOutput(result);
    } else if (key === 'cicd') {
      result = _runCICD(code);
      _setOutput(result);
    }

    if (runBtn) { runBtn.disabled = false; runBtn.innerHTML = '<iconify-icon icon="lucide:play"></iconify-icon> Run Code'; }
  }

  function resetSimulator(simKey) {
    const simulator = simulatorTemplates[simKey];
    const codeArea = document.getElementById('simulatorCode');
    const output = document.getElementById('simulatorOutput');
    if (codeArea) codeArea.value = simulator.defaultCode;
    if (output) { output.textContent = 'Click "Run Code" to execute your code...'; }
  }

  // Make functions globally accessible
  window.closeModal = closeModal;
  window.runSimulator = runSimulator;
  window.resetSimulator = resetSimulator;
  window.launchSimulator = launchSimulator;
  window.startQuiz = startQuiz;

  // ===== EVENT LISTENERS =====
  document.addEventListener('DOMContentLoaded', () => {
    // Guide links
    const guideLinks = document.querySelectorAll('.guide-link');
    guideLinks.forEach((link, index) => {
      const guideKeys = ['fullstack-web', 'microservices', 'cicd-pipeline', 'cloud-native', 'mobile-react-native', 'graphql-api'];
      link.addEventListener('click', (e) => {
        e.preventDefault();
        showGuide(guideKeys[index]);
      });
    });

    // Simulator buttons
    const simulatorButtons = document.querySelectorAll('.simulator-btn[data-sim]');
    simulatorButtons.forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        launchSimulator(btn.dataset.sim);
      });
    });
  });
PAGEJS;
require_once __DIR__ . '/../includes/header.php';
?>
<!-- HERO -->
<section class="hero-bg">
  <div class="hero">
    <span class="hero-badge">Professional Development</span>
    <h1 class="hero-title">Training Resources</h1>
    <div class="hero-desc">Comprehensive learning resources to accelerate your team's development skills with coding simulators, quizzes, implementation guides, and certification programs.</div>
  </div>
</section>

<!-- MAIN RESOURCES OVERVIEW -->
<section>
  <div class="section-heading">
    <span class="section-badge">LEARNING PATHS</span>
    <h2 class="section-title">Accelerate Your Skills</h2>
    <p class="section-desc">Choose from our comprehensive training resources designed for developers at every skill level.</p>
  </div>
  <div class="card-grid">
    <div class="resource-card">
      <div class="card-icon"><iconify-icon icon="lucide:code-2"></iconify-icon></div>
      <div class="card-title">Coding Simulators &amp; Cloud Labs</div>
      <div class="card-desc">Practice coding and cloud operations in real-world scenarios with interactive simulators for programming languages, frameworks, and major cloud providers (AWS, Azure, GCP) — no installation required.</div>
      <ul class="feature-list">
        <li class="feature-item"><iconify-icon icon="lucide:check"></iconify-icon>Real-time Code Execution</li>
        <li class="feature-item"><iconify-icon icon="lucide:check"></iconify-icon>Cloud Provider Sandboxes</li>
        <li class="feature-item"><iconify-icon icon="lucide:check"></iconify-icon>DevOps &amp; IaC Labs</li>
      </ul>
      <a href="#simulators" class="card-btn">Explore Simulators</a>
    </div>
    <div class="resource-card">
      <div class="card-icon"><iconify-icon icon="lucide:brain"></iconify-icon></div>
      <div class="card-title">Knowledge Assessments</div>
      <div class="card-desc">Test your understanding with comprehensive quizzes covering various software development topics and technologies.</div>
      <ul class="feature-list">
        <li class="feature-item"><iconify-icon icon="lucide:check"></iconify-icon>Topic-Specific Quizzes</li>
        <li class="feature-item"><iconify-icon icon="lucide:check"></iconify-icon>Detailed Explanations</li>
        <li class="feature-item"><iconify-icon icon="lucide:check"></iconify-icon>Progress Tracking</li>
      </ul>
      <a href="#quizzes" class="card-btn">Take Quiz</a>
    </div>
    <div class="resource-card">
      <div class="card-icon"><iconify-icon icon="lucide:book-open"></iconify-icon></div>
      <div class="card-title">Implementation Guides</div>
      <div class="card-desc">Step-by-step guides for implementing real-world software solutions, from architecture to deployment.</div>
      <ul class="feature-list">
        <li class="feature-item"><iconify-icon icon="lucide:check"></iconify-icon>Detailed Instructions</li>
        <li class="feature-item"><iconify-icon icon="lucide:check"></iconify-icon>Best Practices</li>
        <li class="feature-item"><iconify-icon icon="lucide:check"></iconify-icon>Code Examples</li>
      </ul>
      <a href="#guides" class="card-btn">View Guides</a>
    </div>
    <div class="resource-card">
      <div class="card-icon"><iconify-icon icon="lucide:award"></iconify-icon></div>
      <div class="card-title">Certification Programs</div>
      <div class="card-desc">Professional certification programs to validate your team's expertise and advance their careers.</div>
      <ul class="feature-list">
        <li class="feature-item"><iconify-icon icon="lucide:check"></iconify-icon>Industry-Recognized</li>
        <li class="feature-item"><iconify-icon icon="lucide:check"></iconify-icon>Multiple Levels</li>
        <li class="feature-item"><iconify-icon icon="lucide:check"></iconify-icon>Team Development</li>
      </ul>
      <a href="#certifications" class="card-btn">Get Certified</a>
    </div>
  </div>
</section>

<!-- CODING SIMULATORS -->
<section id="simulators">
  <div class="section-heading">
    <span class="section-badge">INTERACTIVE LEARNING</span>
    <h2 class="section-title">Coding Simulators &amp; Cloud Labs</h2>
    <p class="section-desc">Practice programming and cloud operations in real-time with our interactive coding environments and sandbox labs for AWS, Azure, and GCP — no local installation or cloud costs required.</p>
  </div>
  <div class="simulator-grid">
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="logos:javascript"></iconify-icon></div>
      <div class="simulator-name">JavaScript Playground</div>
      <div class="simulator-desc">Learn modern JavaScript, ES6+, and asynchronous programming.</div>
      <a href="#" class="simulator-btn" data-sim="javascript">Launch Simulator</a>
    </div>
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="logos:python"></iconify-icon></div>
      <div class="simulator-name">Python Lab</div>
      <div class="simulator-desc">Master Python fundamentals, data structures, and algorithms.</div>
      <a href="#" class="simulator-btn" data-sim="python">Launch Simulator</a>
    </div>
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="logos:react"></iconify-icon></div>
      <div class="simulator-name">React Builder</div>
      <div class="simulator-desc">Build interactive UIs with React components and hooks.</div>
      <a href="#" class="simulator-btn" data-sim="react">Launch Simulator</a>
    </div>
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="logos:nodejs-icon"></iconify-icon></div>
      <div class="simulator-name">Node.js Studio</div>
      <div class="simulator-desc">Create backend services with Node.js and Express.</div>
      <a href="#" class="simulator-btn" data-sim="nodejs">Launch Simulator</a>
    </div>
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="logos:java"></iconify-icon></div>
      <div class="simulator-name">Java Workshop</div>
      <div class="simulator-desc">Practice Java programming and OOP concepts.</div>
      <a href="#" class="simulator-btn" data-sim="java">Launch Simulator</a>
    </div>
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="logos:typescript-icon"></iconify-icon></div>
      <div class="simulator-name">TypeScript Arena</div>
      <div class="simulator-desc">Learn type-safe JavaScript with TypeScript.</div>
      <a href="#" class="simulator-btn" data-sim="typescript">Launch Simulator</a>
    </div>
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="logos:docker-icon"></iconify-icon></div>
      <div class="simulator-name">Docker Sandbox</div>
      <div class="simulator-desc">Practice containerization and Docker workflows.</div>
      <a href="#" class="simulator-btn" data-sim="docker">Launch Simulator</a>
    </div>
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="logos:kubernetes"></iconify-icon></div>
      <div class="simulator-name">K8s Simulator</div>
      <div class="simulator-desc">Deploy and manage Kubernetes clusters hands-on.</div>
      <a href="#" class="simulator-btn" data-sim="kubernetes">Launch Simulator</a>
    </div>
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="logos:aws"></iconify-icon></div>
      <div class="simulator-name">AWS Cloud Lab</div>
      <div class="simulator-desc">Hands-on AWS CLI sandbox for EC2, S3, Lambda, IAM, and more.</div>
      <a href="#" class="simulator-btn" data-sim="aws">Launch Simulator</a>
    </div>
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="logos:microsoft-azure"></iconify-icon></div>
      <div class="simulator-name">Azure Sandbox</div>
      <div class="simulator-desc">Practice Azure CLI commands for VMs, App Services, and storage.</div>
      <a href="#" class="simulator-btn" data-sim="azure">Launch Simulator</a>
    </div>
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="logos:google-cloud"></iconify-icon></div>
      <div class="simulator-name">GCP Cloud Shell</div>
      <div class="simulator-desc">Explore Google Cloud with simulated gcloud, gsutil, and GKE commands.</div>
      <a href="#" class="simulator-btn" data-sim="gcp">Launch Simulator</a>
    </div>
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="logos:terraform-icon"></iconify-icon></div>
      <div class="simulator-name">Terraform Lab</div>
      <div class="simulator-desc">Write and simulate infrastructure-as-code with Terraform HCL.</div>
      <a href="#" class="simulator-btn" data-sim="terraform">Launch Simulator</a>
    </div>
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="devicon:bash"></iconify-icon></div>
      <div class="simulator-name">Bash Terminal</div>
      <div class="simulator-desc">Practice shell scripting and Linux commands in a safe environment.</div>
      <a href="#" class="simulator-btn" data-sim="bash">Launch Simulator</a>
    </div>
    <div class="simulator-card">
      <div class="simulator-icon"><iconify-icon icon="logos:github-actions"></iconify-icon></div>
      <div class="simulator-name">CI/CD Pipeline Lab</div>
      <div class="simulator-desc">Simulate GitHub Actions and CI/CD pipeline configurations end-to-end.</div>
      <a href="#" class="simulator-btn" data-sim="cicd">Launch Simulator</a>
    </div>
  </div>
</section>

<!-- QUIZZES -->
<section id="quizzes">
  <div class="section-heading">
    <span class="section-badge">TEST YOUR KNOWLEDGE</span>
    <h2 class="section-title">Knowledge Assessments</h2>
    <p class="section-desc">Validate your understanding with comprehensive quizzes across various topics.</p>
  </div>
  <div class="quiz-container">
    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Backend &amp; API Development
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>10 Questions per level</span>
        <span><iconify-icon icon="lucide:clock"></iconify-icon>10 Minutes</span>
        <span><span class="quiz-level-pill beginner">Beginner</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your knowledge of REST APIs, GraphQL, authentication, middleware, and distributed patterns.</div>
      <a href="Quizzes/backend-api.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Cloud Architecture &amp; DevOps
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>10 Questions per level</span>
        <span><iconify-icon icon="lucide:clock"></iconify-icon>10 Minutes</span>
        <span><span class="quiz-level-pill beginner">Beginner</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Advanced assessment covering AWS, Azure, CI/CD, containerization, orchestration, and cloud patterns.</div>
      <a href="Quizzes/cloud-devops.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Security Best Practices
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>10 Questions per level</span>
        <span><iconify-icon icon="lucide:clock"></iconify-icon>10 Minutes</span>
        <span><span class="quiz-level-pill beginner">Beginner</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Evaluate your knowledge of application security, OWASP Top 10, cryptography, and secure coding practices.</div>
      <a href="Quizzes/security-practices.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        HTML
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your HTML knowledge from basic tags and forms to advanced semantic elements, accessibility, and performance.</div>
      <a href="Quizzes/html.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        CSS
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Assess your CSS skills across selectors, the box model, Flexbox, Grid, animations, and modern CSS features.</div>
      <a href="Quizzes/css.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        JavaScript
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Deepen your JavaScript knowledge covering syntax, ES6+, closures, async patterns, and advanced internals.</div>
      <a href="Quizzes/javascript.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        TypeScript
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your TypeScript skills from basic type annotations and interfaces to generics, decorators, and advanced type system patterns.</div>
      <a href="Quizzes/typescript.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        React
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Challenge yourself on React components, hooks, state management, context, and performance optimization techniques.</div>
      <a href="Quizzes/react.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Angular
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Evaluate your Angular expertise from components and data binding to NgRx, change detection strategies, and SSR.</div>
      <a href="Quizzes/angular.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Vue.js
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your Vue.js skills covering templates, components, Composition API, Pinia, Vue Router, and Nuxt.js concepts.</div>
      <a href="Quizzes/vuejs.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Bootstrap
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Assess your Bootstrap knowledge from the grid system and utilities to components, JavaScript plugins, and customization.</div>
      <a href="Quizzes/bootstrap.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        jQuery
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your jQuery expertise in DOM manipulation, event handling, AJAX, animations, and plugin development.</div>
      <a href="Quizzes/jquery.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Node.js
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Challenge your Node.js skills from core modules and npm through Express, streams, worker threads, and production deployment.</div>
      <a href="Quizzes/nodejs.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Python
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Evaluate your Python knowledge from syntax and data structures to OOP, decorators, generators, and advanced patterns.</div>
      <a href="Quizzes/python.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Django
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your Django skills covering models, views, templates, ORM, REST framework, and deployment best practices.</div>
      <a href="Quizzes/django.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Java
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Assess your Java expertise from OOP fundamentals and collections to concurrency, JVM internals, and design patterns.</div>
      <a href="Quizzes/java.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        PHP
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your PHP knowledge from syntax and functions to OOP, Composer, frameworks, and modern PHP best practices.</div>
      <a href="Quizzes/php-quiz.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        C
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Evaluate your C programming skills from variables and control flow to pointers, memory management, and systems programming.</div>
      <a href="Quizzes/c-lang.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        C++
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Challenge your C++ knowledge covering OOP, templates, the STL, move semantics, and modern C++ features.</div>
      <a href="Quizzes/cpp.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        C#
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your C# skills from language fundamentals and LINQ to async/await, .NET libraries, and advanced patterns.</div>
      <a href="Quizzes/csharp.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Go
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Assess your Go programming knowledge from basic syntax and goroutines to interfaces, generics, and the Go runtime.</div>
      <a href="Quizzes/golang.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        SQL
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your SQL knowledge covering queries, joins, aggregations, indexes, transactions, and query optimization.</div>
      <a href="Quizzes/sql.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        MySQL
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Evaluate your MySQL expertise from basic queries and schema design to stored procedures, replication, and performance tuning.</div>
      <a href="Quizzes/mysql.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        MongoDB
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your MongoDB knowledge from CRUD operations and aggregation to indexing, replica sets, sharding, and Atlas.</div>
      <a href="Quizzes/mongodb.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        NumPy
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Assess your NumPy skills covering arrays, shapes, broadcasting, linear algebra, random number generation, and performance.</div>
      <a href="Quizzes/numpy.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Pandas
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your Pandas expertise from Series and DataFrames to merging, groupby, time series, and performance optimization.</div>
      <a href="Quizzes/pandas.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Git
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Evaluate your Git knowledge from basic commits and branching through rebasing, hooks, and Git internals.</div>
      <a href="Quizzes/git.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Docker
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Challenge your Docker skills from containers and Dockerfiles to Compose, networking, Swarm, and container security.</div>
      <a href="Quizzes/docker.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        XML
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your XML knowledge covering syntax, DTD, XML Schema, XPath, XSLT, and XML processing techniques.</div>
      <a href="Quizzes/xml.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Swift
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your Swift knowledge from basic syntax and optionals to protocols, generics, concurrency, and advanced Swift internals.</div>
      <a href="Quizzes/swift.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        SASS/SCSS
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Assess your SASS/SCSS skills covering variables, nesting, mixins, extends, functions, and advanced stylesheet architecture.</div>
      <a href="Quizzes/sass.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Generative AI
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Explore Generative AI concepts from foundational models and prompt engineering to fine-tuning, RAG, and responsible AI practices.</div>
      <a href="Quizzes/genai.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        SciPy
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Challenge your SciPy knowledge spanning numerical integration, optimization, linear algebra, signal processing, and scientific computing.</div>
      <a href="Quizzes/scipy.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        AWS
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your AWS knowledge from core services like EC2 and S3 to advanced topics including serverless, networking, and cloud architecture.</div>
      <a href="Quizzes/aws.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Data Science
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Evaluate your Data Science skills covering data wrangling, statistics, visualisation, feature engineering, and model evaluation.</div>
      <a href="Quizzes/data-science.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Intro to Programming
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Begin your coding journey with fundamental programming concepts including variables, loops, functions, and problem-solving techniques.</div>
      <a href="Quizzes/intro-programming.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Intro to HTML &amp; CSS
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Start building web pages with this beginner-friendly quiz on HTML structure, CSS styling, and foundational web design concepts.</div>
      <a href="Quizzes/intro-html-css.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Bash
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Test your Bash skills from basic shell commands and scripting to pipelines, process management, and advanced shell programming.</div>
      <a href="Quizzes/bash.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Rust
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Challenge yourself on Rust fundamentals including ownership, borrowing, lifetimes, traits, concurrency, and systems programming patterns.</div>
      <a href="Quizzes/rust.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        Machine Learning
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Deepen your Machine Learning knowledge from supervised and unsupervised learning to neural networks, model evaluation, and MLOps.</div>
      <a href="Quizzes/machine-learning.php" class="quiz-btn">Start Quiz</a>
    </div>

    <div class="quiz-card">
      <div class="quiz-title">
        <iconify-icon icon="lucide:brain"></iconify-icon>
        AI Tool Development
      </div>
      <div class="quiz-meta">
        <span><iconify-icon icon="lucide:list"></iconify-icon>100 Levels</span>
        <span><iconify-icon icon="lucide:layers"></iconify-icon>5 Tiers</span>
        <span><span class="quiz-level-pill beginner">Intro</span></span>
        <span><span class="quiz-level-pill intermediate">Intermediate</span></span>
        <span><span class="quiz-level-pill advanced">Advanced</span></span>
      </div>
      <div class="card-desc">Master AI tool development covering LLM APIs, prompt engineering, RAG pipelines, agents, embeddings, and production AI application patterns.</div>
      <a href="Quizzes/ai-tools.php" class="quiz-btn">Start Quiz</a>
    </div>
  </div>
</section>

<!-- IMPLEMENTATION GUIDES -->
<section id="guides">
  <div class="section-heading">
    <span class="section-badge">STEP-BY-STEP TUTORIALS</span>
    <h2 class="section-title">Implementation Guides</h2>
    <p class="section-desc">Detailed guides to help you build production-ready applications from scratch.</p>
  </div>
  <div class="guides-grid">
    <div class="guide-card">
      <div class="guide-number">1</div>
      <div class="guide-content">
        <div class="guide-title">Building a Full-Stack Web Application</div>
        <div class="guide-desc">Complete guide to building a modern web application with React frontend, Node.js backend, and MongoDB database. Includes authentication, real-time features, and deployment strategies.</div>
        <div class="guide-topics">
          <span class="topic-tag">React</span>
          <span class="topic-tag">Node.js</span>
          <span class="topic-tag">MongoDB</span>
          <span class="topic-tag">JWT Auth</span>
          <span class="topic-tag">REST API</span>
        </div>
        <a href="#" class="guide-link">
          Read Guide <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
      </div>
    </div>

    <div class="guide-card">
      <div class="guide-number">2</div>
      <div class="guide-content">
        <div class="guide-title">Microservices Architecture Implementation</div>
        <div class="guide-desc">Learn how to design and implement a scalable microservices architecture using Docker, Kubernetes, and service mesh patterns. Covers inter-service communication, data management, and observability.</div>
        <div class="guide-topics">
          <span class="topic-tag">Microservices</span>
          <span class="topic-tag">Docker</span>
          <span class="topic-tag">Kubernetes</span>
          <span class="topic-tag">API Gateway</span>
        </div>
        <a href="#" class="guide-link">
          Read Guide <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
      </div>
    </div>

    <div class="guide-card">
      <div class="guide-number">3</div>
      <div class="guide-content">
        <div class="guide-title">CI/CD Pipeline Setup</div>
        <div class="guide-desc">Step-by-step guide to setting up automated testing and deployment pipelines using GitHub Actions, Jenkins, or GitLab CI. Includes automated testing, code quality checks, and multi-environment deployment.</div>
        <div class="guide-topics">
          <span class="topic-tag">CI/CD</span>
          <span class="topic-tag">GitHub Actions</span>
          <span class="topic-tag">Testing</span>
          <span class="topic-tag">Automation</span>
        </div>
        <a href="#" class="guide-link">
          Read Guide <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
      </div>
    </div>

    <div class="guide-card">
      <div class="guide-number">4</div>
      <div class="guide-content">
        <div class="guide-title">Cloud-Native Application Development</div>
        <div class="guide-desc">Build cloud-native applications leveraging AWS, Azure, or Google Cloud Platform services. Covers serverless architecture, managed databases, caching strategies, and scalability patterns.</div>
        <div class="guide-topics">
          <span class="topic-tag">AWS</span>
          <span class="topic-tag">Serverless</span>
          <span class="topic-tag">Lambda</span>
          <span class="topic-tag">DynamoDB</span>
        </div>
        <a href="#" class="guide-link">
          Read Guide <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
      </div>
    </div>

    <div class="guide-card">
      <div class="guide-number">5</div>
      <div class="guide-content">
        <div class="guide-title">Mobile App Development with React Native</div>
        <div class="guide-desc">Create cross-platform mobile applications using React Native. Includes navigation, state management, native module integration, and publishing to app stores.</div>
        <div class="guide-topics">
          <span class="topic-tag">React Native</span>
          <span class="topic-tag">Mobile</span>
          <span class="topic-tag">iOS</span>
          <span class="topic-tag">Android</span>
        </div>
        <a href="#" class="guide-link">
          Read Guide <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
      </div>
    </div>

    <div class="guide-card">
      <div class="guide-number">6</div>
      <div class="guide-content">
        <div class="guide-title">GraphQL API Implementation</div>
        <div class="guide-desc">Design and build a GraphQL API with type safety, efficient data fetching, and real-time subscriptions. Covers schema design, resolvers, authentication, and performance optimization.</div>
        <div class="guide-topics">
          <span class="topic-tag">GraphQL</span>
          <span class="topic-tag">Apollo</span>
          <span class="topic-tag">Schema Design</span>
          <span class="topic-tag">Subscriptions</span>
        </div>
        <a href="#" class="guide-link">
          Read Guide <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- CERTIFICATION PROGRAMS -->
<section id="certifications">
  <div class="section-heading">
    <span class="section-badge">PROFESSIONAL DEVELOPMENT</span>
    <h2 class="section-title">Certification Programs</h2>
    <p class="section-desc">Industry-recognized certifications to validate expertise and accelerate team development.</p>
  </div>
  <div class="cert-grid">
    <div class="cert-card">
      <div class="cert-badge"><iconify-icon icon="lucide:award"></iconify-icon></div>
      <div class="cert-level">Foundation</div>
      <div class="cert-title">Certified Software Developer</div>
      <div class="cert-desc">Entry-level certification covering fundamental programming concepts, software development lifecycle, and coding best practices.</div>
      <div class="cert-stats">
        <div class="cert-stat">
          <span class="cert-stat-value">12</span>
          <span class="cert-stat-label">Weeks</span>
        </div>
        <div class="cert-stat">
          <span class="cert-stat-value">$599</span>
          <span class="cert-stat-label">Per Person</span>
        </div>
      </div>
      <a href="#" class="card-btn">Enroll Now</a>
    </div>

    <div class="cert-card">
      <div class="cert-badge"><iconify-icon icon="lucide:trophy"></iconify-icon></div>
      <div class="cert-level">Professional</div>
      <div class="cert-title">Full-Stack Web Developer</div>
      <div class="cert-desc">Advanced certification for building complete web applications with modern frontend and backend technologies, databases, and deployment.</div>
      <div class="cert-stats">
        <div class="cert-stat">
          <span class="cert-stat-value">16</span>
          <span class="cert-stat-label">Weeks</span>
        </div>
        <div class="cert-stat">
          <span class="cert-stat-value">$999</span>
          <span class="cert-stat-label">Per Person</span>
        </div>
      </div>
      <a href="#" class="card-btn">Enroll Now</a>
    </div>

    <div class="cert-card">
      <div class="cert-badge"><iconify-icon icon="lucide:crown"></iconify-icon></div>
      <div class="cert-level">Expert</div>
      <div class="cert-title">Cloud Solutions Architect</div>
      <div class="cert-desc">Expert-level certification for designing and implementing scalable cloud architectures on AWS, Azure, or Google Cloud Platform.</div>
      <div class="cert-stats">
        <div class="cert-stat">
          <span class="cert-stat-value">20</span>
          <span class="cert-stat-label">Weeks</span>
        </div>
        <div class="cert-stat">
          <span class="cert-stat-value">$1,499</span>
          <span class="cert-stat-label">Per Person</span>
        </div>
      </div>
      <a href="#" class="card-btn">Enroll Now</a>
    </div>

    <div class="cert-card">
      <div class="cert-badge"><iconify-icon icon="lucide:shield"></iconify-icon></div>
      <div class="cert-level">Specialist</div>
      <div class="cert-title">DevOps Engineer</div>
      <div class="cert-desc">Specialized certification covering CI/CD pipelines, infrastructure as code, containerization, and continuous monitoring.</div>
      <div class="cert-stats">
        <div class="cert-stat">
          <span class="cert-stat-value">14</span>
          <span class="cert-stat-label">Weeks</span>
        </div>
        <div class="cert-stat">
          <span class="cert-stat-value">$1,199</span>
          <span class="cert-stat-label">Per Person</span>
        </div>
      </div>
      <a href="#" class="card-btn">Enroll Now</a>
    </div>

    <div class="cert-card">
      <div class="cert-badge"><iconify-icon icon="lucide:lock"></iconify-icon></div>
      <div class="cert-level">Specialist</div>
      <div class="cert-title">Security Engineer</div>
      <div class="cert-desc">Comprehensive security certification covering application security, penetration testing, secure coding, and compliance standards.</div>
      <div class="cert-stats">
        <div class="cert-stat">
          <span class="cert-stat-value">16</span>
          <span class="cert-stat-label">Weeks</span>
        </div>
        <div class="cert-stat">
          <span class="cert-stat-value">$1,299</span>
          <span class="cert-stat-label">Per Person</span>
        </div>
      </div>
      <a href="#" class="card-btn">Enroll Now</a>
    </div>

    <div class="cert-card">
      <div class="cert-badge"><iconify-icon icon="lucide:users"></iconify-icon></div>
      <div class="cert-level">Team</div>
      <div class="cert-title">Team Development Program</div>
      <div class="cert-desc">Customized training program for teams with flexible curriculum, dedicated support, and group certification options.</div>
      <div class="cert-stats">
        <div class="cert-stat">
          <span class="cert-stat-value">Custom</span>
          <span class="cert-stat-label">Duration</span>
        </div>
        <div class="cert-stat">
          <span class="cert-stat-value">Contact</span>
          <span class="cert-stat-label">For Pricing</span>
        </div>
      </div>
      <a href="/Contact/" class="card-btn">Contact Us</a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<?php require_once __DIR__ . '/../includes/footer.php'; ?>