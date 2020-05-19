import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OverrideAuthorizationComponent } from './override-authorization.component';

describe('OverrideAuthorizationComponent', () => {
  let component: OverrideAuthorizationComponent;
  let fixture: ComponentFixture<OverrideAuthorizationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OverrideAuthorizationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OverrideAuthorizationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
