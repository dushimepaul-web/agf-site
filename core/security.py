import bcrypt

MIN_PASSWORD_LENGTH = 8
MAX_PASSWORD_BYTES = 72


def password_strength_error(password):
    pwd = str(password or "")
    if len(pwd.encode("utf-8")) > MAX_PASSWORD_BYTES:
        return "Le mot de passe ne doit pas dépasser 72 caractères"
    if len(pwd) < MIN_PASSWORD_LENGTH:
        return "Le mot de passe doit contenir au moins 8 caractères"
    if not any(c.isalpha() for c in pwd) or not any(c.isdigit() for c in pwd):
        return "Le mot de passe doit contenir au moins une lettre et un chiffre"
    return None


def _normalize_hash(password_hash):
    if password_hash.startswith("$2y$"):
        return "$2b$" + password_hash[4:]
    return password_hash


def verify_password(password, password_hash):
    if not password or not password_hash:
        return False
    try:
        return bcrypt.checkpw(str(password).encode("utf-8"), _normalize_hash(password_hash).encode("utf-8"))
    except (ValueError, TypeError):
        return False


def hash_password(password):
    hashed = bcrypt.hashpw(str(password).encode("utf-8"), bcrypt.gensalt()).decode("utf-8")
    if hashed.startswith("$2b$"):
        hashed = "$2y$" + hashed[4:]
    return hashed
