import { NgModule } from  '@angular/core';
import {MatInputModule} from '@angular/material/input'; 
import {MatIconModule} from '@angular/material/icon';
import {MatToolbarModule} from '@angular/material/toolbar';  
import {MatMenuModule} from '@angular/material/menu'; 
import {MatCardModule} from '@angular/material/card';
import {MatSidenavModule} from '@angular/material/sidenav';
import {MatListModule} from '@angular/material/list';
import {MatTabsModule} from '@angular/material/tabs'; 
import {MatButtonModule} from '@angular/material/button'; 
import {MatFormFieldModule} from '@angular/material/form-field';
import {MatExpansionModule} from '@angular/material/expansion'; 
import {MatSelectModule} from '@angular/material/select';
import {MatTableModule} from '@angular/material/table'; 
import {MatDialogModule} from '@angular/material/dialog'

 


@NgModule({
    imports: [
    MatIconModule,
    MatToolbarModule,
    MatInputModule,
    MatMenuModule, 
    MatCardModule,
    MatSidenavModule,
    MatListModule,
    MatTabsModule,
    MatButtonModule,
    MatFormFieldModule,
    MatExpansionModule,
    MatSelectModule,
    MatTableModule,
    MatDialogModule
    
    
    ],
    exports: [
    MatIconModule,
    MatToolbarModule,
    MatInputModule,
    MatMenuModule,
    MatCardModule,
    MatSidenavModule,
    MatListModule,
    MatTabsModule,
    MatButtonModule,
    MatFormFieldModule,
    MatExpansionModule,
    MatSelectModule,
    MatTableModule,
    MatDialogModule
    
    ]
})

export  class  MyMaterialModule { }