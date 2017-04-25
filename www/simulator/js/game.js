const eventExample = '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> . @prefix ewe-presence: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-presence/ns/#> . @prefix ewe: <http://gsi.dit.upm.es/ontologies/ewe/ns/#> . @prefix ewe-presence: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-presence/ns/#> . ewe-presence:PresenceSensor1 rdf:type ewe-presence:PresenceDetectedAtDistance. ewe-presence:PresenceSensor1 ewe:sensorID "1a2b3c". ewe-presence:PresenceSensor1 ewe:distance #DISTANCE1#. ewe-presence:PresenceSensor2 rdf:type ewe-presence:PresenceDetectedAtDistance. ewe-presence:PresenceSensor2 ewe:sensorID "4d5e6f". ewe-presence:PresenceSensor2 ewe:distance #DISTANCE2#. ewe-presence:PresenceSensor3 rdf:type ewe-presence:PresenceDetectedAtDistance. ewe-presence:PresenceSensor3 ewe:sensorID "7g8h9i". ewe-presence:PresenceSensor3 ewe:distance #DISTANCE3#.';

// Variables
const mazeWidth = 796;
const mazeHeight = 601;
let newY = 0;
let newX = 0;

// Create the canvas
let canvas = document.createElement('canvas');
let div = document.getElementById('canvas-div');

let ctx = canvas.getContext('2d');

canvas.width = 796;
canvas.height = 601;

div.appendChild(canvas);
document.getElementById('canvas-row').appendChild(div);

// Background image
let bgReady = false;
let bgImage = new Image();
bgImage.onload = function () {
    bgReady = true;
};
bgImage.src = 'simulator/images/background.png';

// Hero image
let heroReady = false;
let heroImage = new Image();
heroImage.onload = function () {
    heroReady = true;
};
heroImage.src = 'simulator/images/hero.png';

// Beacons
let blueBeaconReady = false;
let blueBeaconImage = new Image();
blueBeaconImage.onload = function(){
    blueBeaconReady = true;
}
blueBeaconImage.src = 'simulator/images/bluebeacon.png';

let greenBeaconReady = false;
let greenBeaconImage = new Image();
greenBeaconImage.onload = function(){
    greenBeaconReady = true;
}
greenBeaconImage.src = 'simulator/images/greenbeacon.png';

let purpleBeaconReady = false;
let purpleBeaconImage = new Image();
purpleBeaconImage.onload = function(){
    purpleBeaconReady = true;
}
purpleBeaconImage.src = 'simulator/images/purplebeacon.png';


// Game objects
let hero = {
    speed: 2  // movement in pixels per second
};
let blueBeacon = {};
let greenBeacon = {};
let purpleBeacon = {};

// Handle keyboard controls
let keysDown = {};

let distanceWalked = 0;
const sendEventsAtDistance = 250;

addEventListener('keydown', function (e) {
    keysDown[e.keyCode] = true;
}, false);

addEventListener('keyup', function (e) {
    delete keysDown[e.keyCode];
}, false);

// Reset the game
let reset = function () {
    hero.x = canvas.width / 2;
    hero.y = canvas.height / 2 - 75;

    // Beacon positioning.
    blueBeacon.x = 32 + (Math.random() * (canvas.width - 64));
    blueBeacon.y = 32 + (Math.random() * (canvas.height - 64));

    greenBeacon.x = 32 + (Math.random() * (canvas.width - 64));
    greenBeacon.y = 32 + (Math.random() * (canvas.height - 64));

    purpleBeacon.x = 32 + (Math.random() * (canvas.width - 64));
    purpleBeacon.y = 32 + (Math.random() * (canvas.height - 64));
};

// Update game objects
let update = function (modifier) {
    if (38 in keysDown) { // Player holding up
        newY = hero.y - hero.speed;
        newX = hero.x;
        distanceWalked += hero.speed;
        console.log('up');
    } else if (40 in keysDown) { // Player holding down
        newY = hero.y + hero.speed;
        newX = hero.x;
        distanceWalked += hero.speed;
        console.log('down');
    } else if (37 in keysDown) { // Player holding left
        newX = hero.x - hero.speed;
        newY = hero.y;
        distanceWalked += hero.speed;
        console.log('left');
    } else if (39 in keysDown) { // Player holding right
        newX = hero.x + hero.speed;
        newY = hero.y;
        distanceWalked += hero.speed;
        console.log('right');
    } else if(distanceWalked >= sendEventsAtDistance){
        sendEvent();
        distanceWalked = 0;
    }

    if(canMoveTo(newX, newY)){
        hero.x = newX;
        hero.y = newY;
    }

};

// Draw everything
let render = function() {
    if (bgReady) {
        ctx.drawImage(bgImage, 0, 0);
    }

    if (heroReady) {
        ctx.drawImage(heroImage, hero.x, hero.y);
    }

    if (blueBeaconReady){
        ctx.drawImage(blueBeaconImage, blueBeacon.x, blueBeacon.y);
    }

    if (greenBeaconReady){
        ctx.drawImage(greenBeaconImage, greenBeacon.x, greenBeacon.y);
    }

    if (purpleBeaconReady){
        ctx.drawImage(purpleBeaconImage, purpleBeacon.x, purpleBeacon.y);
    }
};

// The main game loop
let main = function () {
    let now = Date.now();
    let delta = now - then;

    update(delta / 1000);
    render();

    then = now;

    // Request to do this again ASAP
    requestAnimationFrame(main);
};

// Cross-browser support for requestAnimationFrame
const w = window;
requestAnimationFrame = w.requestAnimationFrame || w.webkitRequestAnimationFrame || w.msRequestAnimationFrame || w.mozRequestAnimationFrame;

function canMoveTo(destX, destY) {
    makeWhite(hero.x, hero.y, 26, 56);
    const imgData = ctx.getImageData(destX, destY, 26, 56);
    const data = imgData.data;
    let canMove = 1; // 1 means: the rectangle can move
    if (destX >= 0 && destX <= mazeWidth - 32 && destY >= 0 && destY <= mazeHeight - 56) {  // check whether the rectangle would move inside the bounds of the canvas
        for (let i = 0; i < 4 * 56 * 26; i += 4) {  // look at all pixels
            if (data[i] < 150 && data[i + 1] < 150 && data[i + 2] < 150) {  // black
                canMove = 0;  // 0 means: the rectangle can't move
                break;
            } else if (data[i] === 0 && data[i + 1] === 255 && data[i + 2] === 0) {  // lime: #00FF00
                canMove = 2; // 2 means: the end point is reached
                break;
            }
        }
    } else {
        canMove = 0;
    }
    return canMove;
}

function myMove(e){
    if (dragBlue) {
        blueBeacon.x = e.pageX - canvas.offsetLeft - div.offsetLeft;
        blueBeacon.y = e.pageY - canvas.offsetTop - div.offsetTop;
    } else if (dragGreen) {
        greenBeacon.x = e.pageX - canvas.offsetLeft - div.offsetLeft;
        greenBeacon.y = e.pageY - canvas.offsetTop - div.offsetTop;
    } else if(dragPurple) {
        purpleBeacon.x = e.pageX - canvas.offsetLeft - div.offsetLeft;
        purpleBeacon.y = e.pageY - canvas.offsetTop - div.offsetTop;
    }
}

function myDown(e){
    if (e.pageX < blueBeacon.x + div.offsetLeft + 15 + canvas.offsetLeft && e.pageX > div.offsetLeft + blueBeacon.x - 15 + canvas.offsetLeft && e.pageY < blueBeacon.y + div.offsetTop + 15 + canvas.offsetTop && e.pageY > blueBeacon.y -15 + div.offsetTop + canvas.offsetTop) {
        blueBeacon.x = e.pageX - canvas.offsetLeft - div.offsetLeft;
        blueBeacon.y = e.pageY - canvas.offsetTop - div.offsetTop;
        dragBlue = true;
        canvas.onmousemove = myMove;
    }
    if (e.pageX < greenBeacon.x + div.offsetLeft + 15 + canvas.offsetLeft && e.pageX > div.offsetLeft + greenBeacon.x - 15 + canvas.offsetLeft && e.pageY < greenBeacon.y + div.offsetTop + 15 + canvas.offsetTop && e.pageY > greenBeacon.y -15 + div.offsetTop + canvas.offsetTop){
        greenBeacon.x = e.pageX - canvas.offsetLeft - div.offsetLeft;
        greenBeacon.y = e.pageY - canvas.offsetTop - div.offsetTop;
        dragGreen = true;
        canvas.onmousemove = myMove;
    }
    if (e.pageX < purpleBeacon.x + div.offsetLeft + 15 + canvas.offsetLeft && e.pageX > div.offsetLeft + purpleBeacon.x - 15 + canvas.offsetLeft && e.pageY < purpleBeacon.y + div.offsetTop + 15 + canvas.offsetTop && e.pageY > purpleBeacon.y -15 + div.offsetTop + canvas.offsetTop){
        purpleBeacon.x = e.pageX - canvas.offsetLeft - div.offsetLeft;
        purpleBeacon.y = e.pageY - canvas.offsetTop - div.offsetTop;
        dragPurple = true;
        canvas.onmousemove = myMove;
    }
}

function myUp(){
    dragBlue = false;
    dragGreen = false;
    dragPurple = false;
    canvas.onmousemove = null;
}

function makeWhite(x, y, w, h) {
    ctx.beginPath();
    ctx.rect(x, y, w, h);
    ctx.closePath();
    ctx.fillStyle = 'white';
    ctx.fill();
}

function getDistance(obj1,obj2){
    Obj1Center = [obj1.x + 15, obj1.y + 15];
    Obj2Center = [obj2.x + 15, obj2.y + 28];
    const distance = Math.sqrt(Math.pow(Obj2Center[0] - Obj1Center[0], 2) + Math.pow(Obj2Center[1] - Obj1Center[1], 2));

    return distance / 50;
}

function sendEvent(){
    distanceWalked = 0;
    sendExample = eventExample.replace('#DISTANCE1#', getDistance(blueBeacon, hero));
    sendExample = sendExample.replace('#DISTANCE2#', getDistance(greenBeacon, hero));
    sendExample = sendExample.replace('#DISTANCE3#', getDistance(purpleBeacon, hero));
    console.log(sendExample);
    $.ajax({
        // la URL para la petición
        url : '../controllers/eventsManager.php',
     
        // la información a enviar
        // (también es posible utilizar una cadena de datos)
        data : {
            inputEvent : sendExample,
            user : document.getElementById('user').innerHTML
        },
     
        // especifica si será una petición POST o GET
        type : 'POST',
     
        // el tipo de información que se espera de respuesta
        dataType : 'json',
     
        // código a ejecutar si la petición es satisfactoria;
        // la respuesta es pasada como argumento a la función
        success : function(json) {
            console.log(json);
        },
     
        // código a ejecutar si la petición falla;
        // son pasados como argumentos a la función
        // el objeto de la petición en crudo y código de estatus de la petición
        error : function(xhr, status) {
            console.log(xhr);
            console.log(status);
            alert('Disculpe, existió un problema');
        },
     
        // código a ejecutar sin importar si la petición falló o no
        complete : function(xhr, status) {
            keysDown = {};
        }
    });
}
// Let's play this game!
let then = Date.now();
reset();
main();
canvas.onmousedown = myDown;
canvas.onmouseup = myUp;
