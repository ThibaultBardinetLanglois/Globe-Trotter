/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

const burgerMenu = document.querySelector(".burger-icon");
const menuAppear = document.querySelector(".connexion-links");
const line1 = document.querySelector(".line1");
const line2 = document.querySelector(".line2");
const line3 = document.querySelector(".line3");

burgerMenu.addEventListener('click', () => {
    menuAppear.classList.toggle('change');
    line1.classList.toggle('rotate1');
    line2.classList.toggle('disapear');
    line3.classList.toggle('rotate2');
});
