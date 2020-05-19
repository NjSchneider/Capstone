import { Component, OnInit, Inject } from '@angular/core';
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { BuySellService } from 'src/app/Services/Buy-Sell/buy-sell.service';
import { InventoryItem } from 'src/app/Interfaces/Inventory-Items/inventory-item';
import { CookieService } from 'ngx-cookie-service';
import { InventoryService } from 'src/app/Services/Inventory/inventory.service';

@Component({
  selector: 'app-edit-inventory',
  templateUrl: './edit-inventory.component.html',
  styleUrls: ['./edit-inventory.component.css']
})
export class EditInventoryComponent implements OnInit {

  id: string;
  product: InventoryItem;

  constructor(private search: BuySellService, private inventory: InventoryService, private cookies: CookieService, public dialogRef: MatDialogRef<EditInventoryComponent>, @Inject(MAT_DIALOG_DATA) data) { 
    this.id = data.id;
    console.log(this.id);
  }

  close() {
    this.dialogRef.close();
  }

  update(){
    if(this.product.used === 'New'){
      this.product.used = '1';
    }
    else{
      this.product.used = '0';
    }
    this.cookies.set("product", JSON.stringify(this.product));
    this.inventory.updateInventoryItem().subscribe(result =>{
      console.log(result);
    });
    this.dialogRef.close();
  }

  ngOnInit(): void {
    this.search.getProduct(this.id).subscribe((result: object) =>{
      var used;
      if(result[0]['used'] == 1){
        used = 'New';
      }
      else{
        used = 'Used';
      }
      this.product = {id: result[0]['productID'], name: result[0]['name'], description: result[0]['description'], price : Number(result[0]['price']), used: used, stock: Number(result[0]['stock'])};
      console.log(this.product);

      this.cookies.set("product", JSON.stringify(this.product));
      console.log(encodeURIComponent(this.cookies.get('product')))
    });    
  }

}
