# RecapEnglish (English Grammar Master Book & LMS)

**RecapEnglish** is a digital, user-friendly interactive book and learning management system designed for BCS, University Admission, Medical, and Bank Job aspirants. Built with Laravel 13 and Tailwind CSS, it synthesizes 15 comprehensive lecture notes into an organized 4-part curriculum with mnemonics and interactive practice questions.

---

## 🔑 Access Credentials

The database comes pre-seeded with three tiers of user accounts:

| Role | Name | Email | Password | Access URL & Permissions |
| :--- | :--- | :--- | :--- | :--- |
| **Super Admin** | Super Administrator | `superadmin@recapenglish.com` | `password123` | `/admin`<br>• Full system control<br>• **Only role that can provision or delete Admins**<br>• Manage all parts, chapters, topics & MCQs |
| **Admin** | Academic Editor | `admin@recapenglish.com` | `password123` | `/admin`<br>• Manage existing & newly added topics<br>• Create and edit chapters & practice MCQs<br>• Cannot access admin user management (`/admin/admins`) |
| **Learner / Student** | Tanvir Ahmed | `student@recapenglish.com` | `password123` | `/`<br>• Public reader & study dashboard<br>• Auto-saved last read topic & progress tracking<br>• Topic bookmarking & interactive MCQ drill |

> **Note on Registration:** Public self-registration (`/register` or Google OAuth) strictly creates **Learner (`user`)** accounts. Admin accounts cannot be self-registered and can only be provisioned by the Super Admin.

---

## 📘 Curriculum & Book Structure

The curriculum is structured across 4 progressive parts containing 15 chapters and all **1,641 unabridged past competitive exam questions** (BCS, Primary, University Admission, Bank, and Ministry exams) cross-matched with original lecture notes:

| # | Chapter Title | Topic Scope | Question Count | Answer Keys |
|---|:---|:---|:---:|:---:|
| 1 | **Nouns & Classification** | Proper, Common, Collective, Material, Abstract, Personified Nouns | **89** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| 2 | **Countability & Determiners** | *Few vs Little*, *Many vs Much*, Mass Nouns, Quantifiers | **131** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| 3 | **Pronouns & Substituting Words** | Personal, Reflexive, Emphatic, Demonstrative, Indefinite, **Try To Solve (30 Qs)** | **119** | `ⓐⓑⓒⓓ` + Answer Grid $\rightarrow$ A, B, C, D, E |
| 4 | **Relative Pronouns & Clauses** | *Who, Whom, Whose, Which, That*, Antecedent rules | **83** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| 5 | **Adjectives & Classification** | Attributive, Predicative, Order of Adjectives | **75** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| 6 | **Degree of Comparison** | Positive, Comparative, Superlative, Irregular forms | **117** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| 7 | **Adverbs & Modifiers** | Time, Place, Manner, Degree, Frequency, Dangling Modifiers | **93** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| 8 | **Verbs & Classification** | Finite, Non-Finite, Transitive, Intransitive, Linking, Stative | **106** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| 9 | **Gerunds vs Participles** | Verbal Noun vs Verbal Adjective identification | **72** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| 10 | **Tenses & Right Forms of Verbs** | 12 Tenses, time signals, conditionals, causatives | **238** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| 11 | **Subject-Verb Agreement** | 17 Comprehensive Rules, compound subjects, tricky connectors | **215** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| 12 | **The Subjunctive Mood** | `RUPA IS MODERN` mnemonic, that-clause base forms | **30** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| 13 | **Inversion of Verbs** | `BARSHA-NOVA` mnemonic, negative adverb auxiliary inversion | **35** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| 14 | **Voice Transformation** | Active to Passive, modals, quasi-passive, prepositions | **131** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| 15 | **Narration & Speech** | Direct to Indirect speech across all 5 sentence types | **107** | `ⓐⓑⓒⓓ` $\rightarrow$ A, B, C, D |
| **Total** | **All 15 Chapters** | **Comprehensive English Grammar** | **1,641 MCQs** | **100% Verified** |

---

## ✨ Key Features

1. **Auto-Saved Last Topic ("Resume Reading")**:
   - Automatically tracks every learner's last visited topic and completion percentage.
   - One-click "Resume Reading" button on the reader homepage.
2. **Show / Hide Answer & Interactive Quiz Drill**:
   - Answers and explanations remain **hidden by default** on each question card to encourage active recall.
   - Per-question toggle: **"Show Answer (উত্তর দেখুন)" / "Hide Answer (উত্তর লুকান)"**.
   - Global header toggle: **"Show All Answers (সব উত্তর দেখুন)"** for rapid self-assessment.
   - Real-time interactive feedback (Green for correct, Red with correct answer highlighted for wrong).
   - Instant reset button: **"↺ পুনরায় চেষ্টা করুন (Try Again)"** with dynamic score tracker.
3. **Bangla & English Dual Font Typography**:
   - Integrated `Hind Siliguri` / `Noto Sans Bengali` alongside `Plus Jakarta Sans` with zero broken glyphs or rendering artifacts.
4. **Saved Topics & Bookmarks**:
   - Learners can bookmark tricky topics and add personalized revision notes.
5. **Google OAuth & Email Authentication**:
   - Register and login with standard credentials or one-click Google Sign-In via Laravel Socialite.
6. **Super Admin RBAC & MCQ Management**:
   - Dedicated admin suite to manage topics and questions, plus Super Admin portal to provision editorial staff.

---

## 🚀 Setup & Local Installation

### Prerequisites
- PHP 8.3+
- Composer 2.8+
- Node.js 20+ & npm

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/alimranedx/recapenglish.git
   cd recapenglish
   ```

2. **Install Composer dependencies:**
   ```bash
   composer install
   ```

3. **Install and build front-end assets:**
   ```bash
   npm install
   npm run build
   ```

4. **Environment setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database migration & seeding:**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Start local development server:**
   ```bash
   php artisan serve
   ```
   Or access directly via Laragon at `http://recapenglish.test`.

---

## 🧪 Running Automated Tests

Run the full test suite (covering authentication, RBAC, reader auto-save, bookmarks, and admin provisioning):

```bash
php artisan test
```

---

## 📄 License
This project is open-source under the [MIT License](LICENSE).
