const subheading = document.getElementById('subheading');
const messages = [
  { text:"Unlock Your Academic Potential",color:"black"},
  { text:"Discover the Power of Knowledge",color:"#04af2f"},
  { text:"Chart Your Path to Success",color:"black"}
];

let index = 0;

function displayMessage() {
  subheading.textContent = messages[index].text;
  subheading.style.color=messages[index].color;
  index++;

  if (index === messages.length) {
    index = 0;
  }

  setTimeout(eraseMessage, 2000);
}

function eraseMessage() {
  let text = subheading.textContent;
  let length = text.length;
  let speed = 40;

function eraseCharacter(){
  if (length > 0) {
    subheading.textContent = text.substring(0, length - 1);
    length--;
    setTimeout(eraseMessage, speed);
  } else {
    setTimeout(displayMessage, 1000);
  }
}
eraseCharacter();
}

// Start the animation
displayMessage();
