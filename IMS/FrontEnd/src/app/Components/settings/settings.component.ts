import { Component, OnInit, ViewChild } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { Router } from '@angular/router';
import { Employee } from 'src/app/Interfaces/Employee/employee';
import { LoginService } from 'src/app/Services/Login/login.service';
import { UserService } from 'src/app/Services/User/user.service';
import { MatTableDataSource } from '@angular/material/table';
import { DataSource } from '@angular/cdk/collections';
import { Observable, of as observableOf, merge } from 'rxjs';
import { MatPaginator } from '@angular/material/paginator';
import { MatSort } from '@angular/material/sort';
import { map } from 'rxjs/operators';
import { MatDialog, MatDialogConfig } from '@angular/material/dialog';
import { EditUserComponent } from '../dialogs/edit-user/edit-user.component'

export interface User {
  id: number;
  username: string;
  password: string;
  level: string;
}



@Component({
  selector: 'app-settings',
  templateUrl: './settings.component.html',
  styleUrls: ['./settings.component.css']
})
export class SettingsComponent extends DataSource<User> implements OnInit {

  users: Employee[] = [];
  username: string = "";
  password: string = "";
  level: string = "";
  dataSource = new MatTableDataSource<User>();
  displayedColumns = ['id', 'username', 'password', 'level', 'action'];
  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  searchItem: string;

  constructor(private user: LoginService, private update: UserService, private cookies: CookieService, private router: Router, public dialog: MatDialog) { super(); }

  
  ngOnInit(): void {
    if(this.cookies.get('loggedIn') != 'true'){       
      this.router.navigate(['/login']);
    }
    else{
      this.user.getUsers().subscribe((result: Employee[])=>{
        for(var i = 0; i < result.length; i++){
          this.users[i] = {id: result[i]['userID'], username: result[i]['username'], password: result[i]['password'], level: result[i]['level'], show: true};
          
        }
        this.dataSource.data = this.users;
        console.log(this.users)
      });
    }
  }

  hidePass(pass) {
    let str = '';
    for(let i = 0; i < pass.length; i++) {
      str += '*'
    }
    return str;
  }

  addEmployee(username, password, level){
    console.log("in function")
    var user = {username: username, password: password, level: level};
    console.log(user);
    this.update.addUser(encodeURIComponent(JSON.stringify(user))).subscribe((result) =>{});
    this.username = "";
    this.password = "";
    this.level = "";
    this.user.getUsers().subscribe((result: Employee[])=>{
      for(var i = 0; i < result.length; i++){
        this.users[i] = {id: result[i]['userID'], username: result[i]['username'], password: result[i]['password'], level: result[i]['level'], show: true};
        
      }
      this.dataSource.data = this.users;
    });
  }

  connect(): Observable<User[]> {
    // Combine everything that affects the rendered data into one update
    // stream for the data-table to consume.
    const dataMutations = [
      observableOf(this.dataSource.data),
      this.paginator.page,
      this.sort.sortChange
    ];

    return merge(...dataMutations).pipe(map(() => {
      return this.getPagedData(this.getSortedData([...this.dataSource.data]));
    }));
  }
  private getPagedData(data: User[]) {
    const startIndex = this.paginator.pageIndex * this.paginator.pageSize;
    return data.splice(startIndex, this.paginator.pageSize);
  }

  /**
   * Sort the data (client-side). If you're using server-side sorting,
   * this would be replaced by requesting the appropriate data from the server.
   */
  private getSortedData(data: User[]) {
    if (!this.sort.active || this.sort.direction === '') {
      return data;
    }

    return data.sort((a, b) => {
      const isAsc = this.sort.direction === 'asc';
      switch (this.sort.active) {
        case 'id': return compare(+a.id, +b.id, isAsc);
        case 'name': return compare(a.username, b.username, isAsc);
        case 'pass': return compare(a.password, b.password, isAsc);
        case 'level': return compare(+a.level, +b.level, isAsc);
        default: return 0;
      }
    });
  }
  applyFilter(event: Event) {
    const filterValue = (event.target as HTMLInputElement).value;
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }
  openDialog(id): void {
    const dialogConfig = new MatDialogConfig();
  
    dialogConfig.width = '600px';
    dialogConfig.height = '400px';
    dialogConfig.data = {
      id: id
    };
    
    const dialogRef = this.dialog.open(EditUserComponent, dialogConfig);
  }

  /**
   *  Called when the table is being destroyed. Use this function, to clean up
   * any open connections or free any held resources that were set up during connect.
   */
  disconnect() {}

}
function compare(a: string | number, b: string | number, isAsc: boolean) {
  return (a < b ? -1 : 1) * (isAsc ? 1 : -1);
}
