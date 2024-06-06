import openai
from dotenv import load_dotenv
import os
import sys
import random

# Function to interact with the OpenAI API and generate a question from a random passage.
def openai_chat():
    # Load environment variables from a .env file
    load_dotenv()
    # Get the OpenAI API key from the environment variables
    openai.api_key = os.getenv('OPENAI_API_KEY')
    
    # Template to show the model how to format the responses
    template = """
    Sentence: India won the 1983 Cricket World Cup which was the 3rd edition of the Cricket World Cup tournament.
    Question: Which edition of the world cup did India win?  Answer: third
    Sentence: Google was founded on September 4, 1998, by Larry Page and Sergey Brin.
    Question: In which year was Google founded? Answer: 1998
    Sentence: Google was founded on September 4, 1998, by Larry Page and Sergey Brin.
    Question: Who founded google? Answer: Larry Page and Sergey Brin
    """
    
    # List of passages that can be randomly selected from
    passages = ["""
    Hydrocarbon processing involves a series of chemical and heat processes to convert raw materials into an array of essential products such as gasoline, kerosene,olefins, rubber, and plastics. At the heart of these processes are Fired Heaters, commonly called furnaces. Fired heaters are used to heat the process fluid
    to the desired temperature. The fired heaters are used with many processes including distillation, reforming, olefins manufacturing and hydrocracking. As the operator, it is your responsibility to operate your furnaces as efficiently and safely as possible.
    Efficient furnace operations have become a major concern with the rising cost of fuel. Today, the fuel for processing is about two third of the total utility cost. Most of this fuel is used in Fired Heaters. Environmental regulations have also made it necessary to closely monitor emissions from furnaces and other processing equipment.
    fired heaters, often referred to as furnace, is an equipment used in process industry to heat process fluids to the desired temperature by the burning of the fuel.""",
    
    """HEAT TRANSFER AND COMBUSTION
    Basics of Heat Transfer
    Fired heaters or furnaces are used to transfer heat. Heat is produced by the combustion of fuel at the burners. This heat is then transferred to the process fluid that circulates through the tubes of the furnace.
    There are three methods of heat transfer:
    RADIATION
    CONVECTION
    CONDUCTION
    1. Radiation
    Radiation is how heat is transferred from the sun to the earth. Radiant heat travels in waves as light does.
    For this type of heat transfer to occur, the object being heated must be in direct line of sight with the source of heat.
    As with visible light, these radiant heat waves can be reflected.
    Similarly, in a furnace, radiation takes place in radiant tubes, present in the radiation section. The radiation section is where the tubes receive almost all
    its heat by radiation from the flame. These tubes are exposed to direct heat from the burner.
    Radiation is the transfer of energy from a heat source to objects in its path through space.
    It does not require any material medium for its propagation and may also occur through the vacuum.	
    2. Convection
    Convection involves the flow of gas or liquid to carry heat. A central heating system is a typical example of heat transfer by convection. This type of heat transfer distributes heat throughout
    a room with a flow of hot gases.
    Similarly, convective heat transfer in a furnace takes place in convection tubes. These tubes are located above and out of sight of the burner. As hot gas rises through the furnace they transfer heat to these tubes.
    Heat transfer happens due to the bulk movement of fluids and molecular collision.
    3. Conduction
    When a metal rod is held in a candle flame, the heat goes from the candle to the hand through the metal rod. This process of heat transfer without the movement of particles is called conduction.
    The amount of heat transfer is proportional to the material of the body, the surface area of the body, and the temperature difference. Conduction is the most significant means of heat transfer in solids.
    Similarly, heat travels through the tube wall to the process flow by conduction. Heat travels from the outer surface of the tube wall to the inner surface by conduction.
    Heat reaches the outer surface of the tube either by radiation or convection. It reaches the inner surface of the tube by conduction. Then a convection current is set up that transfers the heat throughout the liquid in the tube.""",

    """How is heat produced in a furnace?
    This is accomplished by a chemical reaction called combustion.
    Combustion is an exothermic reaction where heat is released due to the reaction.
    The presence of oxygen in the exhaust and the absence of fuel particles in the exhaust indicate complete combustion.
    Rapid chemical reaction occurs when the proper amounts of fuel and oxygen are combined with an ignition source to release heat and exhaust.""",

    """Combustion is the breakdown of a compound in combination with oxygen to form carbon dioxide and water with the release of energy in the form of heat.
    Carbon dioxide and water are the combustion products for a complete combustion reaction.""",

    """For combustion to occur, three components must be present
    at the same time and in the proper proportions.
    Fuel
    Oxygen
    Ignition
    Fuel can be either gas, liquid or solid. Furnaces are designed in such a way that the fuel may be burned either in liquid or gas form. The fuel used in a fired heater may be natural gas, fuel oil or a combination of gas and oil.
    Natural gas is preferred in
    many installations because it is cleaner and burns more consistently than oil.
    Typical Natural Gas flame
    Typical Fuel Oil flame
    Oxygen
    Air is needed for combustion because it provides oxygen which acts as oxidant that reacts with the fuel to produce the heat. A candle placed inside a closed glass will stop burn once the oxygen is finished.
    Ignition
    The final thing needed for combustion is a source of ignition. The ignition source provides the heat energy that is required to start the combustion reaction.
    Once the combustion is started, the heat produced from it is used for the
    propagation of combustion chain reaction.
    Air - Fuel Ratio
    In the operation of a fired heater, it is
    important to maintain the right proportion of fuel to air. The exact amount of
    air for the complete combustion of
    a fuel is called stoichiometric air.
    When this is done properly,
    combustion will be complete and the maximum amount of heat will be generated without any fuel waste.""",

    """Optimum Excess Air for a Furnace
    One of the challenges to the operator is to provide enough oxygen	
    to the furnace to allow complete combustion, while at the same time	
    minimizing excess oxygen to assure efficient use of fuel.
    Complete and Incomplete Combustion
    Under perfect conditions, fuel combines
    exactly with the right amount of oxygen to allow complete or stoichiometric combustion without any unburned fuel or excess oxygen.
    But in reality, the combustion is complex
    and some excess air is always needed to guarantee complete fuel combustion.
    If not, substantial amounts of carbon monoxide are produced, reducing
    efficiency and increasing pollution levels.
    Incomplete combustion occurs when there is not enough oxygen available to burn all the fuel. When this happens, unburned fuel can accumulate in the furnace, which is a potentially dangerous situation. It may lead to an explosion.
    """
    ]

    # Randomly select a passage from the list
    input_prompt = f"Sentence: {random.choice(passages)}"
    # Combine the template and the selected passage to form the complete prompt
    prompt = template + "Sentence: " + input_prompt

    # Request completion from the OpenAI API
    completion = openai.Completion.create(engine="gpt-3.5-turbo-instruct", prompt=prompt, max_tokens=80, temperature=0.7)

    # Get the generated response text
    message = completion.choices[0].text

    # Split the response text into sentences
    output_list = message.split("\n")

    # List to store indices of sentences containing questions
    out_index = []

    # Loop through the sentences to find the ones containing questions
    for idx, sentence in enumerate(output_list):
        if "Question" in sentence:
            out_index.append(idx)

    # If there are any questions found, return the first one
    if out_index:
        question_answer = output_list[min(out_index)]
        question = question_answer.split("Question:")[1].split("Answer:")[0].strip()
        return question

# Main execution block to run the function if the script is executed directly
if __name__ == "__main__":
    if len(sys.argv) == 1:
        result = openai_chat()
        print(result)