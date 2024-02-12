
// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
import { getAuth, GoogleAuthProvider, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyAygwEzzDOPE3u0QgYpQJzd2RnI6KsbGEk",
  authDomain: "studentsafe-f84e3.firebaseapp.com",
  projectId: "studentsafe-f84e3",
  storageBucket: "studentsafe-f84e3.appspot.com",
  messagingSenderId: "669624594218",
  appId: "1:669624594218:web:56b71d2cf93fe9349f7853"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

const user = auth. currentUser;
function updateuserProfile(user) {
     const userName = user. displayName;
     const userEmail = user email;
     const userProfilePicture = user photoURL;
     console. log userEmail)
     // Update the profile section with user data
     document. getElementById("username") textContent = userName;
     document. getElementById( ("email") textContent = userEmail;
     document getElementById("picture") src = userProfilePicture;

}
