export class InventoryItem {

    id: string;
    name: string;
    description: string;
    price: number;
    used: number;
    stock: number;

    constructor(id:string, name:string, description:string, price:number, used:number, stock:number){
        this.id = id;
        this.name = name;
        this.description = description;
        this.price = price;
        this.used  = used;
        this.stock = stock;
    }

}
