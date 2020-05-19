import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import {MyMaterialModule} from './Modules/material.module';
import {FormsModule} from '@angular/forms';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import { AppRoutingModule, RoutingComponent } from './app-routing.module';
import { AppComponent } from './app.component';
import { HttpClientModule } from '@angular/common/http';
import { CookieService } from 'ngx-cookie-service';
import { InventoryComponent } from './Components/inventory/inventory.component';
import { MatTableModule } from '@angular/material/table';
import { MatPaginatorModule } from '@angular/material/paginator';
import { MatSortModule } from '@angular/material/sort';
import { OverrideAuthorizationComponent } from './Components/dialogs/override-authorization/override-authorization.component';
import { MatDialogModule } from '@angular/material/dialog';
import { EditInventoryComponent } from './Components/dialogs/edit-inventory/edit-inventory.component';
import { AddInventoryComponent } from './Components/dialogs/add-inventory/add-inventory.component';
import { ReactiveFormsModule } from '@angular/forms';
import { EditUserComponent } from './Components/dialogs/edit-user/edit-user.component'
import { MatGridListModule } from '@angular/material/grid-list'



@NgModule({
  declarations: [
    AppComponent,
    RoutingComponent,
    InventoryComponent,
    OverrideAuthorizationComponent,
    EditInventoryComponent,
    AddInventoryComponent,
    EditUserComponent
    
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    MyMaterialModule,
    FormsModule,
    HttpClientModule,
    MatTableModule,
    MatPaginatorModule,
    MatSortModule,
    MatDialogModule,
    ReactiveFormsModule,
    MatGridListModule,
  ],
  providers: [CookieService],
  bootstrap: [AppComponent]
})
export class AppModule { }
