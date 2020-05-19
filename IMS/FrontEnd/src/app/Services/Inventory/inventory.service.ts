import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CookieService } from 'ngx-cookie-service';

@Injectable({
  providedIn: 'root'
})
export class InventoryService {

  ping: string;

  constructor(private http: HttpClient, private cookies: CookieService) { }

  getInventory(){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=inventory&f=getall`;
    return this.http.get(this.ping);
  }

  getGames(){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=inventory&f=getgames`
    return this.http.get(this.ping);
  }

  getConsoles(){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=inventory&f=getconsoles`
    return this.http.get(this.ping);
  }

  getGenerations(){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=inventory&f=getgenerations`
    return this.http.get(this.ping);
  }

  getBrands(){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=inventory&f=getbrands`
    return this.http.get(this.ping);
  }

  getBrandByConsole(console){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=inventory&f=getbrand&console=${console}`
    return this.http.get(this.ping);
  }

  getBrandByGeneration(cGen){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=inventory&f=getbrand&generation=${cGen}`
    return this.http.get(this.ping);
  }

  searchInventory(category){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=inventory&f=search&cat=${category}`;
    return this.http.get(this.ping);
  }

  updateInventoryItem(){    
    // %7B%22id%22%3A%22025001458612%22%2C%22name%22%3A%22Nintendo%20DS%22%2C%22description%22%3A%22Nintendo%20Hand-held%20device%20%22%2C%22price%22%3A49.99%2C%22used%22%3A%22No%22%2C%22stock%22%3A7%7D
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=inventory&f=update&product=${encodeURIComponent(this.cookies.get('product'))}`;
    return this.http.get(this.ping);
  }

  addProduct(){    
    // %7B%22id%22%3A%22877083845923%22%2C%22name%22%3A%22AV%20Composite%20Cable%20For%20Nintendo%2064%20N64%20%2F%20GameCube%20%2F%20SNES%20%2C%20Black%22%2C%22description%22%3A%22AV%20Composite%20Cable%20For%20Nintendo%2064%20N64%20%2F%20GameCube%20%2F%20SNES%20%2C%20Black%22%2C%22price%22%3A%224.99%22%2C%22used%22%3A%220%22%2C%22stock%22%3A%2212%22%2C%22category%22%3A%22equipment%22%2C%22genre%22%3A%22horror%22%2C%22console%22%3A%22Nintendo%20Entertainment%20System%22%2C%22generation%22%3A%22%22%2C%22brand%22%3A%22Nintendo%22%7D
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=inventory&f=add&product=${encodeURIComponent(this.cookies.get('product'))}`;
    return this.http.get(this.ping);
  }

}
