import { Injectable } from '@angular/core';
import { Item } from 'src/app/Classes/Cart-Item/item';
import { CookieService } from 'ngx-cookie-service';

@Injectable({
  providedIn: 'root'
})
export class CartService {

  private shoppingCart: Item[] = [];
  private buyList: Item[] = [];

  constructor(private cookies: CookieService) { }

  addToCart(item:Item): void{
    console.log(item);
    this.shoppingCart.push(item);
  }

  setShoppingCart(){
    this.shoppingCart = JSON.parse(this.cookies.get("shoppingCart"));
  }

  getShoppingCart(){
    return this.shoppingCart;
  }

  clearCart(): void{
    this.shoppingCart = [];
  }

  addToBuyList(item:Item): void{
    this.buyList.push(item);
  }

  setBuyList(){
    this.buyList = JSON.parse(this.cookies.get("buyList"));
  }

  getBuyList(){
    return this.buyList;
  }

  clearBuyList(): void{
    this.buyList = [];
  }

}
