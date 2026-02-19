class Queue {
    
    constructor() {
        this.items = [];
    }

    AddItem(item) {
        this.items.push(item);
    }
    
    GetEveryItem()
    {
        return this.items;
    }
    
    ThrowItem() {
        if (this.items.length > 0) {
            return this.items.shift(); //USUWA I ZWRACA - FIFO
        } else {
            return null; 
        }
    }
}

/*
      _________________________________
     / Hello, you really reading this? \
    |  ________________________________/
 ___ \/
(*-*)
 /|\
 / \

*/