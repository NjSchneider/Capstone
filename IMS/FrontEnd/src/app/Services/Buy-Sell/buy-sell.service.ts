import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';
import { CookieService } from 'ngx-cookie-service';

@Injectable({
  providedIn: 'root'
})
export class BuySellService {

  private ping: string;

  constructor(private http: HttpClient, private cookies: CookieService) { }

  getProduct(upc:string):Observable<Object>{
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=sell&upc=${upc}`;
    return this.http.get(this.ping);
  }

  createSellTicket(){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=ticket&f=sell&user=${this.cookies.get('username')}&cart=` + encodeURIComponent(this.cookies.get('shoppingCart'));
    return this.http.get(this.ping);
  }

  createBuyTicket(){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=ticket&f=buy&user=${this.cookies.get('username')}&cart=` + encodeURIComponent(this.cookies.get('buyList'));
    return this.http.get(this.ping);
  }
  
  getAuthprizartionCode(){
    this.ping = "http://localhost:8013/GameWorld/IMS/BackEnd/api/users.php"
    return this.http.get(this.ping);
  }

}
