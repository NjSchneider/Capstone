import { Component } from '@angular/core';
import { SearchService } from './Services/Search/search.service';
import { LoginService } from './Services/Login/login.service';
import { Router } from '@angular/router';
import { BuySellComponent } from './Components/buy-sell/buy-sell.component';
import { BehaviorSubject } from 'rxjs';
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {

  username: string = "";
  level: string = "";
  title = 'FrontEnd';  
  searchItem: string; // holds the search/filter constraint entered by the user
  showFiller: boolean = false;
  
  constructor(private search: SearchService, private userLogin: LoginService, private router: Router, private cookies: CookieService){ }


  loggedIn: string = this.cookies.get('loggedIn');

  ngOnInit(){
    if(this.cookies.get('loggedIn') == 'true'){
      this.username = this.cookies.get('username');
      this.loggedIn = this.cookies.get('loggedIn');
      this.level = this.cookies.get('level');
    }
  }

  // sends the search constraint inputted by the user to the SearchService
  sendSearch(searchItem: string): void{
    this.search.sendSearch(searchItem);
  }

  settings(){
    
  }

  logout(){ 
    this.userLogin.logout();
  }
  

}
