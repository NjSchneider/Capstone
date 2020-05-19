import { Component, OnInit, Inject } from '@angular/core';
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { BuySellService } from 'src/app/Services/Buy-Sell/buy-sell.service';
import { InventoryItem } from 'src/app/Interfaces/Inventory-Items/inventory-item';
import { CookieService } from 'ngx-cookie-service';
import { InventoryService } from 'src/app/Services/Inventory/inventory.service';

@Component({
  selector: 'app-add-inventory',
  templateUrl: './add-inventory.component.html',
  styleUrls: ['./add-inventory.component.css']
})
export class AddInventoryComponent implements OnInit {

  genre = "";
  console = "";
  generation = "";
  upc;
  name;
  description;
  price;
  used;
  stock;
  brand = "";
  category;

  generations = [];
  consoles = [];
  brands = [];
  product: Object;

  constructor(private inventory: InventoryService, private cookies: CookieService, public dialogRef: MatDialogRef<AddInventoryComponent>, @Inject(MAT_DIALOG_DATA) data) { }

  ngOnInit(): void {
    this.inventory.getConsoles().subscribe((result: []) =>{
      for(var i = 0; i < result.length; i++){
        this.consoles.push(result[i]['name']);
      }
      console.log("Consoles" + this.consoles);
    });

    this.inventory.getGenerations().subscribe((result: []) =>{
      for(var i = 0; i < result.length; i++){
        this.generations.push(result[i]['name']);
      }
      console.log("Generations" + this.generations);
    });

    this.inventory.getBrands().subscribe((result: []) =>{
      for(var i = 0; i < result.length; i++){
        this.brands.push(result[i]['name']);
      }
      console.log("Brands" + this.brands);
    });

  }

  /*
  * Exits dialog */ 
  close() {
    this.dialogRef.close();
  }

  /*
  * Gets brand based on console selected */ 
  setConsoleInfo(console){
    this.inventory.getBrandByConsole(console).subscribe((result: any) =>{
      this.brand = result;
    });
  }

  /*
  * Gets brand based on console generation entered */ 
  setGenerationInfo(cGen){
    this.inventory.getBrandByGeneration(cGen).subscribe((result: any)=>{
      this.brand = result;
    })
  }

  /*
  * Adds product to Inventory */ 
  addProduct(){
    this.product = { id: this.upc, name: this.name, description: this.description, price: this.price, used: this.used, stock: this.stock, category: this.category, genre: this.genre, console: this.console, generation: this.generation, brand: this.brand };
    console.log(this.product);
    this.cookies.set("product", JSON.stringify(this.product));
    this.inventory.addProduct().subscribe(result =>{
      console.log(result);
    });
    this.dialogRef.close();
  }

}
