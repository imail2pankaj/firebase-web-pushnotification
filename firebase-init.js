var firebaseConfig = {
    apiKey: "AIzaSyCcdThsAogCsKnNNlQ",
    authDomain: "fir-ation.firebaseapp.com",
    projectId: "fir-ation",
    storageBucket: "fir-ation.appspot.com",
    messagingSenderId: "95865",
    appId: "1:95863:web:b6d7ffd0db004ec7d7e9b2",
    measurementId: "G-8N1X3"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);

var messaging = firebase.messaging();

navigator.serviceWorker.register('firebase-messaging-sw.js')

.then(function (registration) {
    messaging.useServiceWorker(registration);

    messaging.onMessage(function (payload) {

        const notificationTitle = payload.notification.title;
        const notificationOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        let notification = new Notification(notificationTitle, notificationOptions);

        notification.onclick = function(e) {
            e.preventDefault();
            window.open(payload.notification.click_action, '_blank');
        }

        return notification;
    });
})
.catch(function (er) {
    console.log('Error in registering service worker');
});
