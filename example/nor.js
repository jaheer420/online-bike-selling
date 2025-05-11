let openshopping = document.querySelector('.shopping');
let closeshopping = document.querySelector('.closeshopping');
let list = document.querySelector('.list');
let listCard = document.querySelector('.listCard');
let body = document.querySelector('body');
let total = document.querySelector('.total');
let quantity = document.querySelector('.quantity');

openshopping.addEventListener('click', () => {
    body.classList.add('active');
});
closeshopping.addEventListener('click', () => {
    body.classList.remove('active');
});

let products = [
    {
        id: 1,
        name: 'Honda cb350',
        image: 'h2.jpg',
        price: 270000
    },
    {
        id: 2,
        name: 'Bobber 350',
        image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTLf0rQFwUlOTqj23NjISmgDoQah86_BbHyoA&s',
        price: 28000
    },
    {
        id: 3,
        name: 'Adventure 350',
        image: 'h3.jpg',
        price: 275000
    },
    {
        id: 4,
        name: 'Roadster 350 ',
        image: 'hun.jpg',
        price: 26000
    },
    {
        id: 5,
        name: 'Hunter 350',
        image: 'road.jpg',
        price: 30000
    },
    {
        id: 6,
        name: 'Classic 350',
        image: 'adv.jpg',
        price: 245000
    },
];

let listCards = [];

function initApp() {
    products.forEach((value, key) => {
        let newDiv = document.createElement('div');
        newDiv.classList.add('item');
        newDiv.innerHTML = `
            <img src="${value.image}" />
            <div class="title">${value.name}</div>
            <div class="price">${value.price.toLocaleString()}</div>
            <button onclick="addToCard(${key})">Add To Cart</button>        
        `;
        list.appendChild(newDiv);
    });
}
initApp();

function addToCard(key) {
    if (listCards[key] == null) {
        listCards[key] = products[key];
        listCards[key].quantity = 1;
    }
    reloadCard();
}

function reloadCard() {
    listCard.innerHTML = ''; // Clear previous content
    let count = 0;
    let totalprice = 0;
    
    listCards.forEach((value, key) => {
        if (value != null) {
            totalprice += value.price * value.quantity;
            count += value.quantity;
            
            let newDiv = document.createElement('li');
            newDiv.innerHTML = `
                <div><img src="${value.image}" /></div>
                <div>${value.name}</div>
                <div>${value.price.toLocaleString()}</div>
               
                <div>
                   <button onclick="changeQuantity(${key}, ${value.quantity - 1})">-</button>
                   <div class="count">${value.quantity}</div>
                   <button onclick="changeQuantity(${key}, ${value.quantity + 1})">+</button>
                </div>
            `;
            listCard.appendChild(newDiv);
        }
    });

    total.innerText = totalprice.toLocaleString();
    quantity.innerText = count;
}

function changeQuantity(key, newQuantity) {
    if (newQuantity <= 0) {
        delete listCards[key];  // Remove item if quantity is 0
    } else {
        listCards[key].quantity = newQuantity;
    }
    reloadCard();
}
