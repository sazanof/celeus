import mitt from 'mitt'

window.Emitter = new class {
    constructor() {
        this.emitter = mitt()
    }

    emit(event, data = null) {
        this.emitter.emit(event, data)
    }

    on(event, callback) {
        this.emitter.on(event, callback)
    }

    off(event) {
        this.emitter.off(event)
    }
}