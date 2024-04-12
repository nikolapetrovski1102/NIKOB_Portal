#include <iostream>
#include <string>
#include <stdlib.h>

using namespace std;

struct Jazol{
    int info;
    Jazol* link;
    Jazol* prev;
};
struct DPLista{
    Jazol head;
    Jazoltail;
    void init();
    void dodadiPrv(int el);
    void dodadiPosleden(int el);
    void brishiPrv();
    void brishiPosleden();
    void brishiLista(); 
};
void DPLista::init()
{
 head = tail = NULL;
}
void DPLista::dodadiPrv(int el)
{
Jazol pom = new Jazol;
 pom->info = el;
 pom->link = head;
 pom->prev = NULL;
 head = pom;
if (head->link == NULL)
 tail = head;
 else
 {
 (pom->link)->prev = pom;
 }
}
void DPLista::dodadiPosleden(int el)
{
Jazolpom = new Jazol;
 pom->info = el;
 pom->link = NULL;

if (head == NULL)
 {
 pom->prev = NULL;
 tail = head = pom;
 }
else
 {
 pom->prev = tail;
 tail->link = pom;
 tail = pom;
 }
}
void DPLista::brishiPrv()
{
if (head != NULL)
 {
 if (head->link == NULL)
 {
 delete head;
 head = tail = NULL;
 }
 else
 {
 Jazolpom = head;
 head = head->link;
 head->prev = NULL;
 delete pom;
} 
}
}
void DPLista::brishiLista()
{
while (head != NULL)
 brishiPrv();
}
void DPLista::brishiPosleden()
{
if (head != NULL)
 {
 if (head->link == NULL)
 {
 delete head;
 head = tail = NULL;
 }
 else
 {
 Jazol *pom = tail;
 tail = tail->prev;
 tail->link = NULL;
 delete pom;
 }
 }
}

void sortiraj(DPLista &l1, DPLista &l2){
    while(l1.head != NULL){
        Jazol *pom = l1.head->link;
        while (pom != NULL){

        }
    }
}

int main(){

}