export function getToken() {
    return document.head.querySelector('meta[name="csrf-token"]').content
}

export function setToken(token) {
    document.head.querySelector('meta[name="csrf-token"]').content = token
}

