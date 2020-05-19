import { Component, OnInit, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { CookieService } from 'ngx-cookie-service';
import { LoginService } from 'src/app/Services/Login/login.service';
import { Employee } from 'src/app/Interfaces/Employee/employee';
import { UserService } from 'src/app/Services/User/user.service';
import { User } from 'src/app/Classes/User/user';
import { MatTableDataSource } from '@angular/material/table';

@Component({
  selector: 'app-edit-user',
  templateUrl: './edit-user.component.html',
  styleUrls: ['./edit-user.component.css']
})
export class EditUserComponent implements OnInit {

  user: Employee;
  id: string = ""


  constructor(private userService: LoginService, private cookies: CookieService, private update: UserService, public dialogRef: MatDialogRef<EditUserComponent>, @Inject(MAT_DIALOG_DATA) data) { 
    this.id = data.id;
  }

  ngOnInit(): void {

    let idNum = Number(this.id);
    idNum--;

    this.userService.getUsers().subscribe((result: Employee[])=>{
        this.user = {id: result[idNum]['id'], username: result[idNum]['username'], password: result[idNum]['password'], level: result[idNum]['level'], show: true};
        console.log(this.user);
  
        this.cookies.set("user", JSON.stringify(this.user));
        console.log(encodeURIComponent(this.cookies.get('user')))
    });

  }

  /*
  * Updates currently selected user information */ 
  updateEmployee(user){
    this.update.updateUser(encodeURIComponent(JSON.stringify(user))).subscribe((result) =>{
      alert(result);
    });
  }

  /*
  * Closes the dialog */ 
  close() {
    this.dialogRef.close();
  }



}
