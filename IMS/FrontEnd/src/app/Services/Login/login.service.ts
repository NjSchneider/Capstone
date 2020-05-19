import { Injectable } from '@angular/core';
import { HttpClientModule, HttpClient } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';
import { CookieService } from 'ngx-cookie-service';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})

export class LoginService {

  private ping: string;

  constructor(private http: HttpClient, private cookieService: CookieService, private router: Router) { }

    getUsers():Observable<Object>{
      this.ping = "http://localhost:8013/GameWorld/IMS/BackEnd/api/users.php";
      return this.http.get(this.ping);
    }

    setUser(username:string){
      this.cookieService.set('username', username);
      this.setLoginState('true');                  
      this.router.navigate(['/buy-sell']);
    }

    setLevel(level:string){
      this.cookieService.set('level', level);
    }

    setLoginState(state:string){      
      this.cookieService.set('loggedIn', state);
      window.location.reload();
    }

    logout(){
      this.cookieService.set('username', '');
      this.cookieService.set('level', '');
      this.setLoginState('false');    
      this.router.navigate(['/login']);
    }
  
}
