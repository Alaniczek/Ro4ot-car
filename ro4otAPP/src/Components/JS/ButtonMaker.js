class ButtonMakerFromJSON {
    constructor(JSON_Location) {
        this.JSON_Location = JSON_Location;
    }

    //TEMPLATE
    async render(selector) {
        const response = await fetch(this.JSON_Location);
        const data = await response.json();
        const container = document.querySelector(selector);

        if (!container) return;

        // Line from Gemini :> 
        // Object.entries pozwala nam wyciągnąć i nazwę (klucz) i dane (order/category)
        Object.entries(data).forEach(([klucz, dane]) => {
            const btn = this.createButton(klucz, dane.order);
            container.appendChild(btn);
        });
    }

    createButton(KeyName, Order) {
        const btn = document.createElement('button');
        
        btn.type = "submit";
        btn.name = "action";
        btn.value = Order; 
        
        btn.innerText = KeyName; 

// STYLE
        btn.style.fontSize = "20px";
        btn.style.padding = "10px";
        btn.style.margin = "5px"; 

        return btn;
    }
}