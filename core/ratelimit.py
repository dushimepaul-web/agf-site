import hashlib
import time

from django.core.cache import cache


MAX_ATTEMPTS = 5
WINDOW_SECONDS = 900


def _key(ip):
    return "login_attempts_" + hashlib.md5((ip or "").encode("utf-8")).hexdigest()


def get_attempts(ip):
    data = cache.get(_key(ip))
    if isinstance(data, dict) and "count" in data and "time" in data:
        return data
    return {"count": 0, "time": int(time.time())}


def is_limited(ip):
    attempts = get_attempts(ip)
    return attempts["count"] >= MAX_ATTEMPTS and (time.time() - attempts["time"]) < WINDOW_SECONDS


def register_failure(ip):
    attempts = get_attempts(ip)
    attempts["count"] = int(attempts["count"]) + 1
    attempts["time"] = int(time.time())
    cache.set(_key(ip), attempts, WINDOW_SECONDS)


def clear(ip):
    cache.delete(_key(ip))
