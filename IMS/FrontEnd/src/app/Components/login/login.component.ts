import { Component, OnInit, EventEmitter, Output } from '@angular/core';
import { Router } from '@angular/router';
import { LoginService } from 'src/app/Services/Login/login.service';
import { User } from 'src/app/Classes/User/user';
import { CloseScrollStrategy } from '@angular/cdk/overlay';
import { CookieService } from 'ngx-cookie-service';
import { FormControl, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  private users: User[] = [];
  public username: string;
  public password: string;  
  public loginForm: FormGroup;
  incorrectUsername = false;
  incorrectPassword = false;

  constructor(private userLogin: LoginService, private router: Router, private cookies: CookieService) { }

  ngOnInit(): void {
    if(this.cookies.get('loggedIn') == 'true'){
      this.router.navigate(['/buy-sell']);
    }

    this.loginForm = new FormGroup({
      username: new FormControl('', [Validators.required]),
      password: new FormControl('', [Validators.required])
    });
  }

  /*
  * Determines whether the field is blank, if true returns error and message reference */ 
  hasError(controlName: string, errorName: string){
    return this.loginForm.controls[controlName].hasError(errorName);
  }

  ngAfterViewInit(){
    var submit = document.getElementById('password'); 
    submit.addEventListener("keyup", function(event){
      if (event.keyCode === 13) {
        console.log("inside");
        event.preventDefault();
        document.getElementById("loginSubmit").click();
       }
    });
  }

  /*
  * Checks user inputted username and password against registered users */ 
  login(username:string, password:string){  
    this.incorrectUsername = false;
    this.incorrectPassword = false;  
    this.userLogin.getUsers().subscribe((result: any[])=>{
      console.log(result);
      for(var i =0; i < result.length; i++){
        this.users.push(new User(result[i].username, result[i].password, result[i].level));
      }      
      for(var i = 0; i < this.users.length; i++){
        if(username == this.users[i].username){
          if(password == this.users[i].password){
            this.userLogin.setUser(this.username);
            this.userLogin.setLevel(this.users[i].level);
            break;
          }
          else{
            this.incorrectPassword = true;
            break;
          }          
        }
        else if(i == this.users.length -1){
          this.incorrectUsername = true;
        }
      }
    })
    console.log(this.incorrectUsername);
  }

}
