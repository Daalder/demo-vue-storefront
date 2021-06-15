export default class GestureDetection {
    constructor() {
        this.watchingElements = [];
        this.windowTouchEndCallbacks = [];
        this.touchEvents = [];
        this.watchTypes = {
            pinch: 0,
            drag: 1,
        };

        this.pinchDistance = 0;

        window.addEventListener('touchend', this.onTouchEnd.bind(this));
    }

    onElementEvent(element, onTouchStartCallback = null, onTouchMoveCallback = null, onPinchCallback = null, onDragCallback = null) {
        this.watchingElements.push({
            watchFor: this.watchTypes.pinch,
            element: element,
            callbacks: {
                start: onTouchStartCallback,
                move: onTouchMoveCallback,
                pinch: onPinchCallback,
                drag: onDragCallback,
            }
        });

        element.addEventListener("touchstart", this.onTouchStart.bind(this));
        element.addEventListener('touchmove', this.onTouchMove.bind(this));
    }

    onWindowTouchEnd(callback) {
        this.windowTouchEndCallbacks.push(callback);
    }

    executeCallbacks(callbackName, event, argument = null) {
        if(callbackName === 'end') {
            this.windowTouchEndCallbacks.forEach((callback) => {
                callback();
            });
        } else {
            this.watchingElements.forEach((watchingElement) => {
                let callback = watchingElement.callbacks[callbackName];

                if(callback !== null) {
                    if(argument === null) {
                        callback(event);
                    } else {
                        callback(event, argument);
                    }
                }
            });
        }
    }

    onTouchStart(event) {
        if(this.touchEvents.length < 2) {
            // Only support two-finger pinching
            this.touchEvents.push({
                x: event.changedTouches[0].clientX,
                y: event.changedTouches[0].clientY,
                id: event.changedTouches[0].identifier
            });
        }

        this.executeCallbacks('start', event);
    }

    onTouchMove(event) {
        // If user is pinching
        if(this.touchEvents.length === 2) {
            if(event.changedTouches.length !== 2) {
                // In case a touchend event was not triggered (multiple possible reasons)
                this.touchEvents = [];
                return;
            }

            let touchPositionOne = {
                x: event.changedTouches[0].clientX,
                y: event.changedTouches[0].clientY,
            }

            let touchPositionTwo = {
                x: event.changedTouches[1].clientX,
                y: event.changedTouches[1].clientY,
            }

            // Get distance between touches
            let distX = touchPositionOne.x - touchPositionTwo.x;
            let distY = touchPositionOne.y - touchPositionTwo.y;
            let distance = Math.sqrt(Math.pow(distX, 2) + Math.pow(distY, 2));

            this.executeCallbacks('pinch', event, distance);
        } else if(this.touchEvents.length === 1) {
            // User is dragging

            // The starting position the gesture
            let oldPos = {
                x: this.touchEvents[0].x,
                y: this.touchEvents[0].y
            }

            // The new (current) position of the gesture
            let newPos = {
                x: event.changedTouches[0].clientX,
                y: event.changedTouches[0].clientY
            }

            // The distance between the start and current gesture positions
            let totalDragDistance = {
                x: oldPos.x - newPos.x,
                y: oldPos.y - newPos.y,
            }

            this.executeCallbacks('drag', event, totalDragDistance);
        }

        this.executeCallbacks('move', event);
    }

    onTouchEnd(event) {
        this.touchEvents.forEach(function(touchEvent, index, touchEvents) {
            if(touchEvent.id === event.changedTouches[0].identifier) {
                touchEvents.splice(index, 1);
            }
        });

        this.executeCallbacks('end', event);
    }
}
