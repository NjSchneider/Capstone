import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class DataService {

  constructor(private http: HttpClient) { }


  ping: string;

  getTopGames(range:string){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=data&f=games&range=${range}`;
    return this.http.get(this.ping);
  }

  getTopGameSales(range:string){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=data&f=gamesales&range=${range}`;
    return this.http.get(this.ping);
  }

  getTopConsoles(){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=data&f=consoles`;
    return this.http.get(this.ping);
  }

  getTopCategories(range:string){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=data&f=categories&range=${range}`;
    return this.http.get(this.ping);
  }

  getTopEmployees(){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=data&f=employees`;
    return this.http.get(this.ping);
  }

  getSales(range:string){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=data&f=sales&range=${range}`;
    return this.http.get(this.ping);
  }

  getTopDays(range:string){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=data&f=days&range=${range}`;
    return this.http.get(this.ping);
  }

  getAllTimeGames(){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=data&f=topgames`;
    return this.http.get(this.ping);
  }

  getAllTimeEquipment(){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=data&f=topequip`;
    return this.http.get(this.ping);
  }

  getAllTimeMisc(){
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=data&f=topmisc`;
    return this.http.get(this.ping);
  }

}
