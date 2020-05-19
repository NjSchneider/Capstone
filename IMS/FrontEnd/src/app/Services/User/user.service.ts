import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  constructor(private http: HttpClient) { }

  ping: string;

  addUser(user: string){
    // %7B%22id%22%3A%221%22%2C%22username%22%3A%22nick%22%2C%22password%22%3A%22p%22%2C%22level%22%3A%22employee%22%2C%22show%22%3A%22true%22%7D
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=users&f=add&user=${user}`;
    return this.http.get(this.ping);
  }

  updateUser(user: string){
    // %7B%22id%22%3A%221%22%2C%22username%22%3A%22hnic%22%2C%22password%22%3A%22p%22%2C%22level%22%3A%22master%22%2C%22show%22%3A%22true%22%7D
    this.ping = `http://localhost:8013/GameWorld/IMS/BackEnd/api/test.php?action=users&f=update&user=${user}`;
    return this.http.get(this.ping);
  }
}
