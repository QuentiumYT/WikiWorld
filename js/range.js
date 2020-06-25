// Get the displayed number of stars
let starsEl = document.getElementById("starsCount");
// Get input to add a title
let submitEl = document.querySelector("input[type=submit]");
submitEl.title = "Noter " + starsEl.innerText + " étoiles";

// Refresh when updated
function refresh(x) {
    // Set the text to the stars number
    starsEl.innerText = x;
    submitEl.title = "Noter " + x + " étoiles";
}