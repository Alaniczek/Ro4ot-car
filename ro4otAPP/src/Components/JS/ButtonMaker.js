class ButtonMakerFromJSON {
    constructor(jsonLocation) {
        this.jsonLocation = jsonLocation;
    }

    async render(selector, buttonName = "action") {
        try {
            const response = await fetch(`${this.jsonLocation}?v=${Date.now()}`);
            const data = await response.json();
            const container = document.querySelector(selector);

            if (!container) return;

            const fragment = document.createDocumentFragment();

            Object.entries(data).forEach(([key, value]) => {
                const btn = this.createButton(key, value.order, buttonName);
                fragment.appendChild(btn);
            });

            container.appendChild(fragment);
        } catch (err) {
            console.error("Błąd ładowania przycisków:", err);
        }
    }

    createButton(keyName, order, buttonName) {
        const btn = document.createElement('button');
        btn.type = "submit";
        btn.name = buttonName;
        btn.value = order;
        btn.innerText = keyName;

        // Skrócony zapis styli
        Object.assign(btn.style, {
            fontSize: "20px",
            padding: "10px",
            margin: "5px"
        });

        return btn;
    }
}