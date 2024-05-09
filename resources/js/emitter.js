import mitt from 'mitt'

export default class Emitter {
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

window.Emitter = new Emitter()
