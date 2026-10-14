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

The curriculum is structured across 4 progressive parts containing 15 chapters and 225+ past competitive exam questions:

### Part I: The Building Blocks (Parts of Speech)
- **Chapter 1:** Nouns & Classification (Proper, Common, Collective, Material, Abstract, Personified)
- **Chapter 2:** Countability & Determiners (*Few vs Little*, *Many vs Much*, Quantifiers)
- **Chapter 3:** Pronouns & Substituting Words (Personal, Reflexive, Emphatic, Demonstrative, Indefinite)
- **Chapter 4:** Relative Pronouns & Clauses (*Who, Whom, Whose, Which, That*, Antecedents)
- **Chapter 5:** Adjectives & Classification (Attributive, Predicative, Order of Adjectives)
- **Chapter 6:** Degree of Comparison (Positive, Comparative, Superlative, Irregular forms)
- **Chapter 7:** Adverbs & Modifiers (Time, Place, Manner, Degree, Frequency, Sentence modifiers)

### Part II: The Engine of Syntax (Verbs & Verbals)
- **Chapter 8:** Verbs & Classification (Finite, Non-Finite, Transitive, Intransitive, Linking)
- **Chapter 9:** Gerunds vs. Participles (Verbal Nouns vs. Verbal Adjectives identification tests)
- **Chapter 10:** Tenses & Right Forms of Verbs (12 Tenses, time signals, stative verbs, conditions)
- **Chapter 11:** Subject-Verb Agreement (Singular/Plural, compound subjects, tricky connectors)

### Part III: Advanced Syntactic Mechanics
- **Chapter 12:** The Subjunctive Mood (`RUPA IS MODERN` mnemonic, that-clause base form rules)
- **Chapter 13:** Inversion of Verbs (`BARSHA-NOVA` mnemonic, negative adverb auxiliary inversion)

### Part IV: Transformations & Discourse
- **Chapter 14:** Voice Transformation (Active to Passive, modals, quasi-passive, imperatives)
- **Chapter 15:** Narration & Speech (Direct to Indirect conversion across sentence types)

---

## ✨ Key Features

1. **Auto-Saved Last Topic ("Resume Reading")**:
   - Automatically tracks every learner's last visited topic and completion percentage.
   - One-click "Resume Reading" button on the reader homepage.
2. **Interactive Practice Quizzes**:
   - Multiple choice questions with instant feedback (Green/Red highlights).
   - Shows detailed explanation notes and examination references (e.g., 38th BCS, DU A-Unit).
3. **Saved Topics & Bookmarks**:
   - Learners can bookmark tricky topics and add personalized revision notes.
4. **Google OAuth & Email Authentication**:
   - Register and login with standard credentials or one-click Google Sign-In via Laravel Socialite.
5. **Super Admin RBAC**:
   - Dedicated portal for Super Admins to provision and manage editorial staff.

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
